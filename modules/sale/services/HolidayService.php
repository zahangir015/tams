<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\account\services\PaymentTimelineService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HolidayRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;

class HolidayService
{
    private HolidayRepository $holidayRepository;

    public function __construct()
    {
        $this->holidayRepository = new HolidayRepository();
    }

    public function findHoliday(string $uid, $withArray = []): ActiveRecord
    {
        return $this->holidayRepository->findOne($uid, $withArray);
    }

    public static function storeHoliday(array $requestData): bool
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
                        $ticket = $this->holidayRepository->store($ticket);
                        if ($ticket->hasErrors()) {
                            throw new Exception('Ticket create failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                        }

                        // Ticket Supplier data process
                        $ticketSupplier = new TicketSupplier();
                        $ticketSupplier->load(['TicketSupplier' => $ticket->getAttributes(['issueDate', 'eTicket', 'pnrCode', 'airlineId', 'paymentStatus', 'type', 'costOfSale', 'baseFare', 'tax'])]);
                        $ticketSupplier->load(['TicketSupplier' => $requestData['TicketSupplier'][$key]]);
                        $ticketSupplier->ticketId = $ticket->id;
                        $ticketSupplier->serviceCharge = Airline::findOne($ticket->airlineId)->serviceCharge;
                        $ticketSupplier = $this->holidayRepository->store($ticketSupplier);
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


}