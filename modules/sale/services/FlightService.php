<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\components\Uploader;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\account\services\PaymentTimelineService;
use app\modules\admin\models\User;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketRefund;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\repositories\FlightRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;

class FlightService
{
    private FlightRepository $flightRepository;

    public function __construct()
    {
        $this->flightRepository = new FlightRepository();
    }

    public function storeTicket(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Ticket']) || !empty($requestData['TicketSupplier'])) {
                $tickets = [];
                $invoice = null;
                $supplierLedgerArray = [];
                $customer = Customer::findOne(['id' => $requestData['Ticket'][0]['customerId']]);

                foreach ($requestData['Ticket'] as $key => $ticketData) {
                    $ticket = new Ticket();
                    $ticket->scenario = 'create';

                    if(($ticketData['type'] == ServiceConstant::TICKET_TYPE_FOR_CREATE['Reissue']) && !isset($ticketData['motherTicketId'])){
                        throw new Exception('Ticket create failed - Mother ticket is required.');
                    }

                    if ($ticket->load(['Ticket' => $ticketData])) {
                        $ticket = self::processTicketData($ticket);
                        $ticket = $this->flightRepository->store($ticket);
                        if ($ticket->hasErrors()) {
                            throw new Exception('Ticket create failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                        }

                        // Ticket Supplier data process
                        $ticketSupplier = new TicketSupplier();
                        $ticketSupplier->load(['TicketSupplier' => $ticket->getAttributes(['issueDate', 'eTicket', 'pnrCode', 'airlineId', 'paymentStatus', 'type', 'costOfSale', 'baseFare', 'tax'])]);
                        $ticketSupplier->load(['TicketSupplier' => $requestData['TicketSupplier'][$key]]);
                        $ticketSupplier->ticketId = $ticket->id;
                        $ticketSupplier->serviceCharge = Airline::findOne($ticket->airlineId)->serviceCharge;
                        $ticketSupplier = $this->flightRepository->store($ticketSupplier);
                        if ($ticketSupplier->hasErrors()) {
                            throw new Exception('Ticket Supplier create failed - ' . Helper::processErrorMessages($ticketSupplier->getErrors()));
                        }

                        // Invoice details data process
                        if (isset($requestData['invoice']) || ($ticket->type == ServiceConstant::TICKET_TYPE_FOR_CREATE['Reissue'])) {
                            $tickets[] = [
                                'refId' => $ticket->id,
                                'refModel' => Ticket::class,
                                'dueAmount' => $ticket->quoteAmount,
                                'paidAmount' => 0,
                                'supplierData' => [
                                    [
                                        'refId' => $ticketSupplier->id,
                                        'refModel' => $ticketSupplier::class,
                                        'subRefModel' => Invoice::class,
                                        'dueAmount' => $ticketSupplier->costOfSale,
                                        'paidAmount' => $ticketSupplier->paidAmount,
                                    ]
                                ]
                            ];
                        }

                        // Supplier ledger data process
                        if (isset($supplierLedgerArray[$ticketSupplier->supplier->id])) {
                            $supplierLedgerArray[$ticketSupplier->supplier->id]['credit'] += $ticketSupplier->costOfSale;
                        } else {
                            $supplierLedgerArray[$ticketSupplier->supplier->id] = [
                                'debit' => 0,
                                'credit' => $ticketSupplier->costOfSale,
                                'refId' => $ticketSupplier->supplier->id,
                                'refModel' => Supplier::class,
                                'subRefId' => null
                            ];
                        }
                    } else {
                        throw new Exception('Ticket data loading failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                    }
                }

                // Invoice process and create
                if (!empty($tickets)){
                    $autoInvoiceCreateResponse = InvoiceService::autoInvoice($customer->id, $tickets, $requestData['group'], Yii::$app->user);
                    if ($autoInvoiceCreateResponse['error']) {
                        $dbTransaction->rollBack();
                        throw new Exception('Invoice - ' . $autoInvoiceCreateResponse['message']);
                    }
                    $invoice = $autoInvoiceCreateResponse['data'];
                }


                // Supplier Ledger process
                $ledgerRequestResponse = LedgerService::batchInsert($invoice, $supplierLedgerArray);
                if ($ledgerRequestResponse['error']) {
                    $dbTransaction->rollBack();
                    throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Ticket added successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Ticket and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function uploadTicket(UploadedFile $file, array $requestData): array
    {
        // Upload to tmp
        $fileName = Uploader::processFile($file, false);
        $fileData = fopen('uploads/tmp/' . $fileName, "r");

        $airline = Airline::findOne(['id' => $requestData['Ticket']['airlineId']]);
        $customer = Customer::findOne(['id' => $requestData['Ticket']['customerId']]);
        $supplier = Supplier::findOne(['id' => $requestData['TicketSupplier']['supplierId']]);
        $provider = Provider::findOne(['id' => $requestData['Ticket']['providerId']]);

        $data = [];
        $data['invoice'] = 'on';
        $data['group'] = 1;
        $key = 0;
        while (($line = fgetcsv($fileData, 1000, ",")) !== false) {
            if (!empty($line)) {
                if (trim($line[0]) == 'issueDate') {
                    continue;
                }
                $baseFare = $line[5];
                $tax = $line[6];
                $otherTax = $line[7];
                $quoteAmount = $line[8];
                $data['Ticket'][$key] = [
                    'airlineId' => $airline->id,
                    'commission' => $airline->commission,
                    'incentive' => $airline->incentive,
                    'govTax' => $airline->govTax,
                    'type' => GlobalConstant::TICKET_TYPE_FOR_CREATE['New'],
                    'numberOfSegment' => $line[10],
                    'pnrCode' => $line[2],
                    'eTicket' => $line[1],
                    'paxName' => $line[3],
                    'paxType' => $line[4],
                    'seatClass' => $line[11],
                    'providerId' => $provider->id,
                    'route' => $line[9],
                    'issueDate' => date('Y-m-d', strtotime($line[0])),
                    'departureDate' => date('Y-m-d', strtotime($line[12])),
                    'baseFare' => $baseFare,
                    'tax' => $tax,
                    'otherTax' => $otherTax,
                    'quoteAmount' => $quoteAmount,
                    'tripType' => self::tripTypeIdentifier($line[9]),
                    'baggage' => $line[13],
                    'customerId' => $customer->id,
                ];
                $data['TicketSupplier'][$key] = [
                    'supplierId' => $supplier->id,
                    'status' => 1,
                    'paidAmount' => 0,
                ];
                $key++;
            }
        }
        $response = $this->storeTicket($data);
        if (file_exists(getcwd() . '/uploads/tmp/' . $file)) {
            unlink(getcwd() . '/uploads/tmp/' . $file);
        }
        if (!$response) {
            return ['error' => true, 'message' => 'Ticket uploaded Failed.'];
        }
        return ['error' => false, 'message' => 'Ticket uploaded successfully.'];
    }

    public function addRefundTicket(ActiveRecord $motherTicket, array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Store New Refund ticket
            $newRefundTicket = self::storeRefundTicket($motherTicket, $requestData);
            if ($newRefundTicket->hasErrors()) {
                throw new Exception('New Refund Ticket store failed - ' . Helper::processErrorMessages($newRefundTicket->getErrors()));
            }

            // Mother Ticket update
            $motherTicket->type = ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund Requested'];
            $motherTicket->refundRequestDate = $newRefundTicket->refundRequestDate;
            $motherTicket = $this->flightRepository->store($motherTicket);
            if ($motherTicket->hasErrors()) {
                throw new Exception('Mother ticket update failed - ' . Helper::processErrorMessages($motherTicket->getErrors()));
            }

            // Ticket Supplier data process
            $ticketSupplier = self::storeTicketSupplier($newRefundTicket, $requestData);
            if ($ticketSupplier->hasErrors()) {
                throw new Exception('Ticket Supplier store failed - ' . Helper::processErrorMessages($ticketSupplier->getErrors()));
            }

            // Create refund for customer and supplier
            $refundDataProcessResponse = self::processTickerRefundModelData($newRefundTicket, $requestData);
            if ($refundDataProcessResponse['error']) {
                throw new Exception('Ticket refund creation failed - ' . $refundDataProcessResponse['message']);
            }

            // Add refund ticket in invoice
            $invoiceDetailProcessResponse = InvoiceService::addRefundServiceToInvoice($newRefundTicket);
            if ($invoiceDetailProcessResponse['error']) {
                throw new Exception('Invoice creation failed - ' . $invoiceDetailProcessResponse['message']);
            }
            $invoiceDetail = $invoiceDetailProcessResponse['data'];

            // Supplier Ledger process
            $processSupplierLedgerResponse = LedgerService::processSingleSupplierLedger($motherTicket, $ticketSupplier, $invoiceDetail);
            if ($processSupplierLedgerResponse['error']) {
                throw new Exception('Supplier Ledger creation failed - ' . $processSupplierLedgerResponse['message']);
            }

            // Create Service Payment Detail for refund
            /*$servicePaymentDetailData = PaymentTimelineService::storeServicePaymentDetailData(['refundService' => $newRefundTicket, 'parentService' => $motherTicket], $invoiceDetail);
            if ($servicePaymentDetailData['error']) {
                throw new Exception('Service payment detail process failed - ' . $servicePaymentDetailData['message']);
            }*/
            //dd($servicePaymentDetailData);

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', ' Refund Ticket added successfully');
            return true;
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function storeRefundTicket(ActiveRecord $motherTicket, array $requestData): ActiveRecord
    {
        $newRefundTicket = new Ticket();
        $motherTicketData = $motherTicket->getAttributes(null, $except = ['id', 'uid', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'baseFare', 'tax', 'otherTax', 'quoteAmount']);
        $newRefundTicket->load(['Ticket' => $motherTicketData]);
        $newRefundTicket->load($requestData);
        $newRefundTicket->netProfit = ($newRefundTicket->quoteAmount - $newRefundTicket->costOfSale);
        $newRefundTicket->serviceCharge = isset($requestData['TicketRefund']['refundCharge']) ? (double)$requestData['TicketRefund']['supplierRefundCharge'] : (double)$requestData['Ticket']['costOfSale'];

        if (($newRefundTicket->baseFare != 0) || ($newRefundTicket->tax != 0) || ($newRefundTicket->otherTax != 0)) {
            $newRefundTicket->commission = $motherTicket->commission;
            $newRefundTicket->incentive = $motherTicket->incentive;
            $newRefundTicket->commissionReceived = ($newRefundTicket->baseFare * $newRefundTicket->commission);
            $newRefundTicket->incentiveReceived = (($newRefundTicket->baseFare - $newRefundTicket->commissionReceived) * ($newRefundTicket->incentive));
        }

        return $this->flightRepository->store($newRefundTicket);
    }

    public function storeTicketSupplier(ActiveRecord $newRefundTicket, array $requestData): ActiveRecord
    {
        $ticketSupplier = new TicketSupplier();
        $ticketSupplier->load(['TicketSupplier' => $newRefundTicket->getAttributes(['issueDate', 'eTicket', 'pnrCode', 'airlineId', 'paymentStatus', 'type', 'costOfSale', 'baseFare', 'tax'])]);
        $ticketSupplier->load(['TicketSupplier' => $requestData['TicketSupplier']]);
        $ticketSupplier->ticketId = $newRefundTicket->id;
        $ticketSupplier->serviceCharge = (double)$requestData['TicketRefund']['airlineRefundCharge'] + (double)$requestData['TicketRefund']['supplierRefundCharge'];
        return $this->flightRepository->store($ticketSupplier);
    }

    private static function processTickerRefundModelData(ActiveRecord $newRefundTicket, mixed $requestData): array
    {
        $referenceData = [
            [
                'refId' => $newRefundTicket->customerId,
                'refModel' => Customer::class,
                'serviceCharge' => $newRefundTicket->quoteAmount,
                'ticketId' => $newRefundTicket->id,
                'refundRequestDate' => $newRefundTicket->refundRequestDate,
                'isRefunded' => 0,
            ],
            [
                'refId' => $newRefundTicket->ticketSupplier->supplierId,
                'refModel' => Supplier::class,
                'serviceCharge' => $newRefundTicket->ticketSupplier->costOfSale,
                'ticketId' => $newRefundTicket->id,
                'refundRequestDate' => $newRefundTicket->refundRequestDate,
                'isRefunded' => 0,
            ]
        ];

        $ticketRefundBatchData = [];
        // Customer Ticket refund data process
        foreach ($referenceData as $ref) {
            $ticketRefund = new TicketRefund();
            if (!$ticketRefund->load($requestData) || !$ticketRefund->load(['TicketRefund' => $ref]) || !$ticketRefund->validate()) {
                return ['error' => true, 'message' => 'Ticket Refund validation failed - ' . Helper::processErrorMessages($ticketRefund->getErrors())];
            }
            $ticketRefundBatchData[] = $ticketRefund->getAttributes(null, ['id']);
        }

        // Ticket Refund batch insert process
        if (empty($ticketRefundBatchData)) {
            return ['error' => true, 'message' => 'Ticket Refund batch data process failed.'];
        }

        if (!FlightRepository::batchStore('ticket_refund', array_keys($ticketRefundBatchData[0]), $ticketRefundBatchData)) {
            return ['error' => true, 'message' => 'Ticket Refund batch insert failed'];
        }

        return ['error' => false, 'message' => 'Ticket Refund process done.'];
    }

    public static function reissueParentChain(int $motherTicketId, int $totalReceived): float
    {
        $parentTicket = Ticket::findOne(['id' => $motherTicketId]);
        $totalReceived += $parentTicket->receivedAmount;
        if ($parentTicket->motherTicketId) {
            return self::reissueParentChain($parentTicket->motherTicketId, $totalReceived);
        }
        return $totalReceived;
    }

    public function updateTicket(array $requestData, Ticket $ticket)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Ticket']) || !empty($requestData['TicketSupplier'])) {
                $oldQuoteAmount = $ticket->quoteAmount;
                $oldCostOfSale = $ticket->costOfSale;

                $ticket->setAttributes($requestData['Ticket'][0]);
                $ticket = self::processTicketData($ticket);
                if (!$ticket->save()) {
                    throw new Exception('Ticket update failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                }

                // Compare old quote and new quote
                $updateServiceQuoteResponse = self::compareOldQuoteAndNewQuoteAndProcessData($ticket, $oldQuoteAmount);
                if ($updateServiceQuoteResponse['error']) {
                    throw new Exception($updateServiceQuoteResponse['message']);
                }

                $ticketSupplier = $ticket->ticketSupplier;
                $oldSupplierId = $ticketSupplier->supplierId;
                $ticketSupplier->load(['TicketSupplier' => $ticket->getAttributes(['issueDate', 'eTicket', 'pnrCode', 'airlineId', 'paymentStatus', 'type'])]);
                $ticketSupplier->load(['TicketSupplier' => $requestData['TicketSupplier'][0]]);
                $ticketSupplier->costOfSale = $ticket->costOfSale;
                if (!$ticketSupplier->update()) {
                    throw new Exception('Ticket supplier update failed - ' . Helper::processErrorMessages($ticketSupplier->getErrors()));
                }

                // supplier Ledger update
                if (!empty($ticket->invoice) && ($ticketSupplier->costOfSale != $oldCostOfSale)) {

                    if ($ticket->ticketSupplier->supplierId != $oldSupplierId) {
                        $suppliersLedgerData[] = [
                            'title' => 'Service Purchase Update',
                            'reference' => 'Invoice Number - ' . $ticket->invoice->invoiceNumber,
                            'refId' => $oldSupplierId,
                            'refModel' => Supplier::class,
                            'subRefId' => $ticket->invoiceId,
                            'subRefModel' => $ticket->invoice::className(),
                            'debit' => 0,
                            'credit' => 0
                        ];
                        if (!TicketSupplier::updateAll(['status' => 0, 'updatedBy' => Yii::$app->user->id, 'updatedAt' => Helper::convertToTimestamp(date('Y-m-d h:i:s'))], ['id' => $oldSupplierId])) {
                            throw new Exception(Helper::processErrorMessages('Ticket Supplier delete failed.'));
                        }
                    }

                    $suppliersLedgerData[] = [
                        'title' => 'Service Purchase Update',
                        'reference' => 'Invoice Number - ' . $ticket->invoice->invoiceNumber,
                        'refId' => $ticketSupplier->supplierId,
                        'refModel' => Supplier::class,
                        'subRefId' => $ticket->invoiceId,
                        'subRefModel' => $ticket->invoice::className(),
                        'debit' => 0,
                        'credit' => $ticketSupplier->costOfSale
                    ];

                    foreach ($suppliersLedgerData as $singleLedger) {
                        $ledgerRequestResponse = LedgerService::updateLedger($singleLedger);
                        if (!$ledgerRequestResponse['status']) {
                            throw new Exception(Helper::processErrorMessages('Not update ticket  ' . $ledgerRequestResponse['message']));
                        }
                    }
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Ticket updated successfully');
                return true;
            }
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    protected static function processTicketData(Ticket $ticket): Ticket
    {
        $commissionReceived = self::calculateCommissionReceived($ticket->baseFare, $ticket->commission);
        $incentiveReceived = self::calculateIncentiveReceived($ticket->baseFare, $ticket->commission, $ticket->incentive);
        $ait = self::calculateAIT($ticket->baseFare, $ticket->tax, $ticket->govTax);

        $ticket->commissionReceived = $commissionReceived;
        $ticket->incentiveReceived = $incentiveReceived;
        $ticket->ait = $ait;
        $ticket->tripType = self::tripTypeIdentifier($ticket->route);
        $ticket->costOfSale = self::calculateCostOfSale($ticket->tax, $ticket->serviceCharge, $ait, $ticket->baseFare, $commissionReceived, $incentiveReceived);
        $ticket->flightType = self::flightTypeIdentifier($ticket->route);
        $ticket->netProfit = self::calculateNetProfit($ticket->quoteAmount, $ticket->tax, $ticket->baseFare, $ticket->serviceCharge, $ait, $commissionReceived, $incentiveReceived);
        $ticket->customerCategory = Customer::findOne(['id' => $ticket->customerId])->category;
        if ($ticket->isNewRecord) {
            $ticket->paymentStatus = GlobalConstant::PAYMENT_STATUS['Due'];
            $ticket->receivedAmount = 0;
            $ticket->status = GlobalConstant::ACTIVE_STATUS;
        }
        return $ticket;
    }

    private static function compareOldQuoteAndNewQuoteAndProcessData(Ticket $ticket, float $oldQuoteAmount): array
    {
        if (($oldQuoteAmount != $ticket->quoteAmount) && !empty($ticket->invoice)) {
            // Update Invoice Entity
            $tickets[] = [
                'refId' => $ticket->id,
                'refModel' => Ticket::class,
                'amount' => $ticket->receivedAmount,
                'due' => ($ticket->quoteAmount - $ticket->receivedAmount),
            ];
            return SaleService::updatedServiceRelatedData($ticket, $tickets);

        }

        return ['error' => false, 'message' => 'Quote amount is not updated'];
    }

    public static function calculateAIT(float $baseFare, float $tax, mixed $govtTax): float
    {
        return (((double)$baseFare + (double)$tax) * (double)$govtTax);
    }

    protected static function tripTypeIdentifier($route): string
    {
        $airports = explode('-', $route);
        $totalAirports = count($airports);
        if ($airports[0] == $airports[($totalAirports - 1)]) {
            return GlobalConstant::TRIP_TYPE['Return'];
        }
        return GlobalConstant::TRIP_TYPE['One Way'];
    }

    private static function flightTypeIdentifier(mixed $route): string
    {
        $airports = array_unique(explode('-', $route));
        $international = array_diff($airports, GlobalConstant::BD_AIRPORTS);
        if (empty($international)) {
            return 1;
        }
        return 0;
    }

    private static function calculateCommissionReceived($baseFare, $commission): float
    {
        return ($baseFare * $commission);
    }

    private static function calculateIncentiveReceived($baseFare, $commission, $incentive): float
    {
        return (($baseFare - ($baseFare * $commission)) * ($incentive));
    }

    private static function calculateNetProfit($quoteAmount, $tax, $baseFare, $serviceCharge, $ait, $commissionReceived, $incentiveReceived)
    {
        return ($quoteAmount - ($tax + $serviceCharge + $ait + (($baseFare - $commissionReceived) - $incentiveReceived)));
    }

    private static function calculateCostOfSale($tax, $airlineServiceCharge, $ait, $baseFare, $commissionReceived, $incentiveReceived)
    {
        return ($tax + $airlineServiceCharge + $ait + (($baseFare - $commissionReceived) - $incentiveReceived));
    }

    public static function calculateQuoteAmount($baseFare, $tax, $ait, $requestData): float
    {
        $quoteAmount = $baseFare + $tax;
        // Ticket Discount calculation
        $discount = 0;
        // If there is any convenienceFee
        if (isset($requestData['convenienceFee']) && ($requestData['convenienceFee'] != 0)) {
            $quoteAmount += ($requestData['convenienceFee'] / count($requestData['passenger']));
        }

        // If there is any advanceIncomeTax
        $quoteAmount += floor($ait);
        return ($quoteAmount - $discount);
    }

    public function findTicket(string $uid, $withArray = []): ActiveRecord
    {
        return $this->flightRepository->findOneTicket($uid, $withArray);
    }

    public function ajaxCostCalculation($baseFare, $tax, $airlineId)
    {
        $airline = Airline::findOne(['id' => (int)$airlineId]);
        $commissionReceived = self::calculateCommissionReceived($baseFare, $airline->commission);
        $incentiveReceived = self::calculateIncentiveReceived($baseFare, $airline->commission, $airline->incentive);
        $ait = self::calculateAIT($baseFare, $tax, $airline->govTax);

        return self::calculateCostOfSale($tax, $airline->serviceCharge, $ait, $baseFare, $commissionReceived, $incentiveReceived);
    }


}