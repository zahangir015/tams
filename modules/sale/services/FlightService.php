<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\components\Uploader;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\admin\models\User;
use app\modules\sale\components\SaleConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\Ticket;
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
                $supplierLedgerArray = [];
                $customer = Customer::findOne(['id' => $requestData['Ticket'][0]['customerId']]);

                foreach ($requestData['Ticket'] as $key => $ticketData) {
                    $ticket = new Ticket();
                    $ticket->scenario = 'create';

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
                        if (isset($requestData['invoice'])) {
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
                $autoInvoiceCreateResponse = InvoiceService::autoInvoice($customer->id, $tickets, $requestData['group'], Yii::$app->user);
                if ($autoInvoiceCreateResponse['error']) {
                    $dbTransaction->rollBack();
                    throw new Exception('Invoice - ' . $autoInvoiceCreateResponse['message']);
                }
                $invoice = $autoInvoiceCreateResponse['data'];

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
        if ($response) {
            return ['error' => false, 'message' => 'Ticket uploaded successfully.'];
        }
    }

    public function addRefundTicket(array $requestData, Ticket $motherTicket): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {

            // Store New Refund ticket
            $createNewRefundTicket = self::storeNewRefundTicket($motherTicket, $requestData);
            if (!$createNewRefundTicket['status']) {
                throw new Exception($createNewRefundTicket['message']);
            }
            // Create ticketSupplier
            $createNewTicketSupplier = TicketSupplier::createNewTicketSupplier($createNewRefundTicket['data'], $ticket->ticketSupplier->supplierId);
            if (!$createNewTicketSupplier['status']) {
                throw new Exception('Refund Ticket Supplier creation failed - ' . $createNewTicketSupplier['message']);
            }

            // Update Existing ticket
            $updateExistTicket = self::updateExistTicketForRefundRequest($ticket, Ticket::TICKET_TYPE['Refund Requested']);
            if (!$updateExistTicket['status']) {
                throw new Exception('Existing ticket update failed - ' . $updateExistTicket['message']);
            }
            // Create refund for customer and supplier
            $refundTicket = TicketRefund::storeForCustomerAndSupplier($ticket, Yii::$app->request->post('TicketRefund'), $createNewRefundTicket['data']);
            if (!$refundTicket['status']) {
                throw new Exception('Ticket refund creation failed - ' . $refundTicket['message']);
            }
            // Add refund ticket in invoice
            $invoiceData = RefundComponent::formInvoiceData($createNewRefundTicket['data']);
            $storedInvoiceDetail = InvoiceComponent::createInvoiceDetailForRefund($invoiceData);
            if (!$storedInvoiceDetail['status']) {
                throw new Exception('Invoice creation failed - ' . $storedInvoiceDetail['message']);
            }
            // Supplier Ledger process
            $processSupplierLedgerResponse = RefundComponent::processSupplierLedger($ticket->id, $createNewTicketSupplier['data'], $storedInvoiceDetail['data']->invoiceId);
            if ($processSupplierLedgerResponse['error']) {
                throw new Exception('Supplier Ledger creation failed - ' . $processSupplierLedgerResponse['message']);
            }
            // Create Service Payment Detail for refund
            $servicePaymentDetailData = RefundComponent::storeServicePaymentDetailData(['refundService' => $createNewRefundTicket['data'], 'parentService' => $ticket], $storedInvoiceDetail['data']);
            if ($servicePaymentDetailData['error']) {
                throw new Exception($servicePaymentDetailData['message']);
            }
            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', ' Refund Ticket added successfully');

            return true;
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());

            return false;
        }
    }

    private static function storeNewRefundTicket(Ticket $motherTicket, array $requestData)
    {

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
                            throw new Exception(Utils::processErrorMessages('Ticket Supplier delete failed.'));
                        }
                    }

                    $suppliersLedgerData[] = [
                        'title' => 'Service Purchase Update',
                        'reference' => 'Invoice Number - ' . $ticketModel->invoice->invoiceNumber,
                        'refId' => $ticketSupplier->supplierId,
                        'refModel' => Supplier::class,
                        'subRefId' => $ticketModel->invoiceId,
                        'subRefModel' => $ticketModel->invoice::className(),
                        'debit' => 0,
                        'credit' => $ticketSupplier->costOfSale
                    ];

                    foreach ($suppliersLedgerData as $singleLedger) {
                        $ledgerRequestResponse = LedgerComponent::updateLedger($singleLedger);
                        if (!$ledgerRequestResponse['status']) {
                            throw new Exception(Utils::processErrorMessages('Not update ticket  ' . $ledgerRequestResponse['message']));
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

    private static function calculateAIT(float $baseFare, float $tax, mixed $govtTax): float
    {
        /*$BD = $UT = $E5 = 0;
        if (!empty($taxBreakDown)) {
            foreach (['BD', 'UT', 'E5'] as $taxKey) {
                $key = array_search($taxKey, array_column($taxBreakDown, 'code'));
                if ($key !== false) {
                    $$taxKey = (double)$taxBreakDown[$key]->amount;
                }
            }
            return ((($baseFare + $tax) - ($BD + $UT + $E5)) * $govtTax);
        }*/
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
        return 2;
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


}