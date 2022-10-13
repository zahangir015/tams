<?php

namespace app\modules\sale\services;

use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HolidayRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

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

    public function storeHoliday(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Holiday']) || !empty($requestData['HolidaySupplier'])) {
                $services = [];
                $supplierLedgerArray = [];
                $customer = Customer::findOne(['id' => $requestData['Holiday']['customerId']]);

                $holiday = new Holiday();
                if ($holiday->load($requestData)) {
                    $holiday->type = ServiceConstant::TYPE['New'];
                    $holiday->customerCategory = $customer->category;
                    $holiday = $this->holidayRepository->store($holiday);
                    if ($holiday->hasErrors()) {
                        throw new Exception('Holiday create failed - ' . Helper::processErrorMessages($holiday->getErrors()));
                    }

                    // Holiday Supplier data process
                    $holidaySupplierBatchData = [];
                    foreach ($requestData['HolidaySupplier'] as $singleSupplierArray) {
                        $holidaySupplier = new HolidaySupplier();
                        if (!$holidaySupplier->load($requestData)  || !$holidaySupplier->validate()) {
                            throw new Exception('Holiday Supplier validation failed - ' . Helper::processErrorMessages($holidaySupplier->getErrors()));
                        }
                        $holidaySupplierBatchData[] = $holidaySupplier->getAttributes(null, ['id']);
                    }
                    if (empty($holidaySupplierBatchData)) {
                        throw new Exception('Holiday Supplier batch data can not be empty.');
                    }
                    if (!$this->holidayRepository->batchStore('holiday_supplier`', $holiday)) {
                        throw new Exception('Holiday Supplier batch insert failed.');
                    }

                    // Invoice details data process
                    if (isset($requestData['invoice'])) {
                        $services[] = [
                            'refId' => $holiday->id,
                            'refModel' => Holiday::class,
                            'dueAmount' => $holiday->quoteAmount,
                            'paidAmount' => 0,
                            'supplierData' => [
                                [
                                    'refId' => $holidaySupplier->id,
                                    'refModel' => HolidaySupplier::class,
                                    'subRefModel' => Invoice::class,
                                    'dueAmount' => $holidaySupplier->costOfSale,
                                    'paidAmount' => $holidaySupplier->paidAmount,
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