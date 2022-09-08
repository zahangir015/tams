<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\admin\models\User;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\repositories\FlightRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

class FlightService
{
    private FlightRepository $flightRepository;

    public function __construct()
    {
        $this->flightRepository = new FlightRepository();
    }

    public function storeTicket($requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Ticket']) && !empty($requestData['TicketSupplier'])) {
                $error = false;
                $tickets = [];
                $supplierLedgerArray = [];
                $autoInvoiceCreateResponse = null;
                $customer = Customer::findOne(['id' => $requestData['Ticket'][0]['customerId']]);

                foreach ($requestData['Ticket'] as $key => $ticketData) {
                    $ticket = new Ticket();
                    $ticket->scenario = 'create';
                    $supplier = Supplier::findOne(['id' => $requestData['TicketSupplier'][$key]['supplierId']]);


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
                        $ticketSupplier = $this->flightRepository->store($ticketSupplier);
                        if ($ticketSupplier->hasErrors()) {
                            throw new Exception('Ticket Supplier create failed - ' . Helper::processErrorMessages($ticketSupplier->getErrors()));
                        }

                        // Invoice details data process
                        if (isset($requestData['invoice'])) {
                            $tickets[] = [
                                'refId' => $ticket->id,
                                'refModel' => Ticket::class,
                                'due' => $ticket->quoteAmount,
                                'amount' => 0,
                                'supplierData' => [
                                    [
                                        'refId' => $ticketSupplier->id,
                                        'refModel' => $ticketSupplier::class,
                                        'subRefModel' => Invoice::class,
                                        'due' => $ticketSupplier->costOfSale,
                                        'amount' => $ticketSupplier->paidAmount,
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
                    Yii::$app->session->setFlash('error', 'Invoice - ' . $autoInvoiceCreateResponse['message']);
                    return false;
                }
                $invoice = $autoInvoiceCreateResponse['data'];

                // Ledger process
                $ledgerRequestResponse = LedgerService::batchInsert($invoice, $supplierLedgerArray);
                if ($ledgerRequestResponse['error']) {
                    $dbTransaction->rollBack();
                    Yii::$app->session->setFlash('success', 'Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                    return false;
                }


                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Ticket added successfully');
                return true;
            }
            return false;
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