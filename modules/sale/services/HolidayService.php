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
            // Ticket and supplier data can not be empty
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
            // Ticket and supplier data can not be empty
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
            $invoice = $holiday->invoice;
            $oldQuoteAmount = $holiday->quoteAmount;

            // Update Package
            $holiday->setAttributes($requestData['Holiday']);
            $holiday->netProfit = self::calculateNetProfit($holiday->quoteAmount, $holiday->costOfSale);
            $holiday->paymentStatus = InvoiceService::checkAndDetectPaymentStatus($holiday->quoteAmount, $holiday->receivedAmount);
            if (!$holiday->save()) {
                throw new Exception('Holiday update failed - ' . Helper::processErrorMessages($holiday->getErrors()));
            }

            //Create Package-Supplier Entity
            $suppliers = $requestData['HolidaySupplier'];
            if (!$suppliers) {
                throw new Exception('At least 1 Supplier is required');
            }
            $updateHolidaySupplierResponse = self::updateHolidaySupplier($holiday, $suppliers, $invoice);
            if (!$updateHolidaySupplierResponse['status']) {
                throw new Exception($updateHolidaySupplierResponse['message']);
            }

            if (!empty($invoice) && ($oldQuoteAmount != $holiday->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $holiday->id,
                    'refModel' => get_class($holiday),
                    'due' => ($holiday->quoteAmount - $holiday->receivedAmount),
                    'amount' => $holiday->receivedAmount
                ];

                $updateServiceQuoteResponse = ServiceComponent::updatedServiceRelatedData($holiday, $services);
                if ($updateServiceQuoteResponse['error']) {
                    throw new Exception($updateServiceQuoteResponse['message']);
                }
            }
            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Package has been updated successfully');
            return true;
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }
    }

    private static function updateHolidaySupplier(ActiveRecord $holiday, mixed $suppliers, mixed $invoice)
    {
        $selectedPackageSuppliers = [];
        $deletedSuppliers = [];
        $suppliersLedgerData = [];

        foreach ($reqPackageSuppliers as $packageSupplier) {
            $checkSupplier = Supplier::findOne(['id' => $packageSupplier['supplierId']]);
            if (!$checkSupplier)
                return ['status' => false, 'message' => 'Supplier not found'];

            if (!empty($packageSupplier['id'])) {
                $model = PackageSupplier::findOne(['id' => $packageSupplier['id']]);
                $selectedPackageSuppliers[] = $model->id;
            } else {
                $model = new PackageSupplier();
                $model->packageId = $package->id;
                $model->identificationNo = $package->identificationNo;
                $model->packageCategoryId = $package->packageCategoryId;
                $model->status = Constant::ACTIVE_STATUS;
                $model->paymentStatus = ServiceConstant::PAYMENT_STATUS['Due'];
            }
            $model->setAttributes($packageSupplier);
            $model->type = $packageSupplier['type'] ?? ServiceConstant::TYPE['New'];
            $model->supplierName = $checkSupplier->name;

            if (!$model->save()) {
                return ['status' => false, 'message' => 'Not saved Package Supplier Model- ' . Utils::processErrorMessages($model->getErrors())];
            }
            if (!empty($invoice)) {
                // Supplier Ledger process
                if (isset($suppliersLedgerData[$model->supplierId]['credit'])) {
                    $suppliersLedgerData[$model->supplierId]['credit'] += $model->costOfSale;
                } else {
                    $suppliersLedgerData[$model->supplierId] = [
                        'title' => 'Service Purchase',
                        'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                        'refId' => $model->supplierId,
                        'refModel' => Supplier::className(),
                        'subRefId' => $invoice->id,
                        'subRefModel' => $invoice::className(),
                        'debit' => 0,
                        'credit' => $model->costOfSale
                    ];
                }
            }
        }
        $updateSuppliers = [];
        if (!empty($invoice)) {
            foreach ($package->packageSuppliers as $oldPackageSupplier) {
                if (!in_array($oldPackageSupplier->id, $selectedPackageSuppliers)) {
                    $suppliersLedgerData[$oldPackageSupplier->supplierId] = [
                        'title' => 'Service Purchase Update',
                        'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                        'refId' => $oldPackageSupplier->supplierId,
                        'refModel' => Supplier::class,
                        'subRefId' => $invoice->id,
                        'subRefModel' => $invoice::className(),
                        'debit' => 0,
                        'credit' => 0
                    ];
                    $deletedSuppliers[] = $oldPackageSupplier->id;
                } else {
                    if (isset($updateSuppliers[$oldPackageSupplier->supplierId]['costOfSale'])) {
                        $updateSuppliers[$oldPackageSupplier->supplierId]['costOfSale'] += $oldPackageSupplier->costOfSale;
                    } else {
                        $updateSuppliers[$oldPackageSupplier->supplierId]['costOfSale'] = $oldPackageSupplier->costOfSale;
                    }
                }
            }
            foreach ($suppliersLedgerData as $key => $supplierLedger) {
                if (isset($updateSuppliers[$key]['costOfSale']) && ($updateSuppliers[$key]['costOfSale'] == $supplierLedger['credit'])) {
                    continue;
                }
                $ledgerRequestResponse = LedgerComponent::updateLedger($supplierLedger);
                if (!$ledgerRequestResponse['status']) {
                    return ['status' => false, 'message' => $ledgerRequestResponse['message']];
                }
            }
        }

        // delete removed packageSuppliers
        if (count($deletedSuppliers)) {
            if (!PackageSupplier::updateAll(['status' => Constant::INACTIVE_STATUS, 'updatedBy' => Yii::$app->user->id, 'updatedAt' => Utils::convertToTimestamp(date('Y-m-d h:i:s'))], ['in', 'id', $deletedSuppliers])) {
                return ['status' => false, 'message' => 'Package Supplier not deleted with given supplier id(s)'];
            }
        }

        return ['status' => true, 'message' => 'Package Supplier Saved Successfully'];
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