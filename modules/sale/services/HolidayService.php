<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayCategory;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HolidayRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class HolidayService
{
    private HolidayRepository $holidayRepository;

    public function __construct()
    {
        $this->holidayRepository = new HolidayRepository();
    }

    private static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public function findHoliday(string $uid, $withArray = []): ActiveRecord
    {
        return $this->holidayRepository->findOne($uid, $withArray);
    }

    public function getCategories(): array
    {
        return ArrayHelper::map($this->holidayRepository->findCategories(), 'id', 'name');
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
                    $holidaySupplierProcessedData = self::holidaySupplierProcess($holiday, $requestData['HolidaySupplier']);

                    // Invoice details data process
                    $services[] = [
                        'refId' => $holiday->id,
                        'refModel' => Holiday::class,
                        'dueAmount' => $holiday->quoteAmount,
                        'paidAmount' => 0,
                        'supplierData' => $holidaySupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $holidaySupplierProcessedData['supplierLedgerArray'];

                    // Invoice process and create
                    $autoInvoiceCreateResponse = InvoiceService::autoInvoice($customer->id, $services, 1, Yii::$app->user);
                    if ($autoInvoiceCreateResponse['error']) {
                        throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                    }
                    $invoice = $autoInvoiceCreateResponse['data'];

                    // Supplier Ledger process
                    $ledgerRequestResponse = LedgerService::batchInsert($invoice, $supplierLedgerArray);
                    if ($ledgerRequestResponse['error']) {
                        throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                    }
                } else {
                    throw new Exception('Holiday data loading failed - ' . Helper::processErrorMessages($holiday->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Holiday added successfully');
                return true;
            }
            // Holiday and supplier data can not be empty
            throw new Exception('Holiday and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function refundHoliday(array $requestData, ActiveRecord $motherHoliday): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Holiday']) || !empty($requestData['HolidaySupplier'])) {
                $services = [];
                $invoice = null;
                $customer = Customer::findOne(['id' => $requestData['Holiday']['customerId']]);
                $holiday = new Holiday();
                if ($holiday->load($requestData)) {
                    $holiday->customerCategory = $customer->category;
                    $holiday = $this->holidayRepository->store($holiday);
                    if ($holiday->hasErrors()) {
                        throw new Exception('Holiday refund create failed - ' . Helper::processErrorMessages($holiday->getErrors()));
                    }

                    // Holiday Supplier data process
                    $holidaySupplierProcessedData = self::holidaySupplierProcess($holiday, $requestData['HolidaySupplier']);

                    // Invoice details data process
                    $services[] = [
                        'invoiceId' => $motherHoliday->invoiceId ?? null,
                        'refId' => $holiday->id,
                        'refModel' => Holiday::class,
                        'dueAmount' => ($holiday->quoteAmount - $holiday->receivedAmount),
                        'paidAmount' => $holiday->receivedAmount,
                        'supplierData' => $holidaySupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $holidaySupplierProcessedData['supplierLedgerArray'];

                    if ($motherHoliday->invoiceId) {
                        // Invoice process
                        $autoInvoiceCreateResponse = InvoiceService::autoInvoiceForRefund($motherHoliday->invoice, $services, Yii::$app->user);
                        if ($autoInvoiceCreateResponse['error']) {
                            throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                        }
                        $invoice = $autoInvoiceCreateResponse['data'];
                    }

                    // Supplier Ledger process
                    $ledgerRequestResponse = LedgerService::batchInsert($invoice, $supplierLedgerArray);
                    if ($ledgerRequestResponse['error']) {
                        throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                    }

                } else {
                    throw new Exception('Holiday data loading failed - ' . Helper::processErrorMessages($holiday->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Holiday added successfully');
                return true;
            }
            // Holiday and supplier data can not be empty
            throw new Exception('Holiday and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function updateHoliday(array $requestData, ActiveRecord $holiday): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (empty($requestData['Holiday']) || !empty($requestData['HolidaySupplier'])) {
                throw new Exception('Holiday and supplier data can not be empty.');
            }

            $oldQuoteAmount = $holiday->quoteAmount;
            $oldReceivedAmount = $holiday->receivedAmount;
            $oldCustomerId = $holiday->customerId;

            // Update Holiday Model
            $holiday->load($requestData);
            $holiday->netProfit = self::calculateNetProfit($holiday->quoteAmount, $holiday->costOfSale);
            $holiday->paymentStatus = InvoiceService::checkAndDetectPaymentStatus($holiday->quoteAmount, $holiday->receivedAmount);
            if (!$holiday->save()) {
                throw new Exception('Holiday update failed - ' . Helper::processErrorMessages($holiday->getErrors()));
            }

            // TODO Invoice update
            // TODO Customer ledger update
            // TODO Service Payment timeline update
            $quoteAmountDiff = ($holiday->quoteAmount - $oldQuoteAmount);
            if ($holiday->invoiceId && $quoteAmountDiff) {
                $invoiceUpdateResponse = InvoiceService::updateInvoiceUpdate($holiday->invoice, $holiday, $quoteAmountDiff);
            }

            // Update Holiday Supplier
            // TODO Supplier ledger update
            // TODO Bill update
            $holidaySupplierProcessedData = self::updateHolidaySupplier($holiday, $requestData['HolidaySupplier']);
            // Invoice details data process
            $services[] = [
                'invoiceId' => $motherHoliday->invoiceId ?? null,
                'refId' => $holiday->id,
                'refModel' => Holiday::class,
                'dueAmount' => ($holiday->quoteAmount - $holiday->receivedAmount),
                'paidAmount' => $holiday->receivedAmount,
                'supplierData' => $holidaySupplierProcessedData['serviceSupplierData']
            ];
            $supplierLedgerArray = $holidaySupplierProcessedData['supplierLedgerArray'];


            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Package has been updated successfully');
            return true;
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }
    }

    private static function updateHolidaySupplier(ActiveRecord $holiday, array $suppliers)
    {

        return ['error' => true, 'message' => 'Package Supplier Saved Successfully'];
    }

    private function holidaySupplierProcess(ActiveRecord $holiday, mixed $holidaySuppliers): array
    {
        $serviceSupplierData = [];
        $supplierLedgerArray = [];
        foreach ($holidaySuppliers as $singleSupplierArray) {
            $holidaySupplier = new HolidaySupplier();
            $holidaySupplier->load(['HolidaySupplier' => $singleSupplierArray]);
            $holidaySupplier->holidayId = $holiday->id;
            $holidaySupplier = $this->holidayRepository->store($holidaySupplier);
            if ($holidaySupplier->hasErrors()) {
                throw new Exception('Holiday Supplier refund creation failed - ' . Helper::processErrorMessages($holidaySupplier->getErrors()));
            }

            $serviceSupplierData[] = [
                'refId' => $holidaySupplier->id,
                'refModel' => HolidaySupplier::class,
                'subRefModel' => Invoice::class,
                'dueAmount' => $holidaySupplier->costOfSale,
                'paidAmount' => $holidaySupplier->paidAmount,
            ];

            // Supplier ledger data process
            if (isset($supplierLedgerArray[$holidaySupplier->supplierId])) {
                $supplierLedgerArray[$holidaySupplier->supplierId]['credit'] += $holidaySupplier->costOfSale;
            } else {
                $supplierLedgerArray[$holidaySupplier->supplierId] = [
                    'debit' => 0,
                    'credit' => $holidaySupplier->costOfSale,
                    'refId' => $holidaySupplier->supplierId,
                    'refModel' => Supplier::class,
                    'subRefId' => null
                ];
            }
        }

        return ['serviceSupplierData' => $serviceSupplierData, 'supplierLedgerArray' => $supplierLedgerArray];
    }
}