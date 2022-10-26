<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaSupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\VisaRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

class VisaService
{
    private VisaRepository $visaRepository;

    public function __construct()
    {
        $this->visaRepository = new VisaRepository();
    }

    private static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public function findVisa(string $uid, $withArray = []): ActiveRecord
    {
        return $this->visaRepository->findOne($uid, $withArray);
    }

    public function storeVisa(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Visa']) || !empty($requestData['VisaSupplier'])) {
                $services = [];
                $supplierLedgerArray = [];
                $customer = Customer::findOne(['id' => $requestData['Visa']['customerId']]);
                $visa = new Visa();
                if ($visa->load($requestData)) {
                    $visa->type = ServiceConstant::TYPE['New'];
                    $visa->customerCategory = $customer->category;
                    $visa = $this->visaRepository->store($visa);
                    if ($visa->hasErrors()) {
                        throw new Exception('Visa create failed - ' . Helper::processErrorMessages($visa->getErrors()));
                    }

                    // Visa Supplier data process
                    $visaSupplierProcessedData = self::visaSupplierProcess($visa, $requestData['VisaSupplier']);

                    // Invoice details data process
                    $services[] = [
                        'refId' => $visa->id,
                        'refModel' => Visa::class,
                        'dueAmount' => $visa->quoteAmount,
                        'paidAmount' => 0,
                        'supplierData' => $visaSupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $visaSupplierProcessedData['supplierLedgerArray'];

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
                    throw new Exception('Visa data loading failed - ' . Helper::processErrorMessages($visa->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Visa added successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Visa and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function refundVisa(array $requestData, ActiveRecord $motherVisa): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Visa']) || !empty($requestData['VisaSupplier'])) {
                $services = [];
                $invoice = null;
                $customer = Customer::findOne(['id' => $requestData['Visa']['customerId']]);
                $visa = new Visa();
                if ($visa->load($requestData)) {
                    $visa->customerCategory = $customer->category;
                    $visa = $this->visaRepository->store($visa);
                    if ($visa->hasErrors()) {
                        throw new Exception('Visa refund create failed - ' . Helper::processErrorMessages($visa->getErrors()));
                    }

                    // Visa Supplier data process
                    $visaSupplierProcessedData = self::visaSupplierProcess($visa, $requestData['VisaSupplier']);

                    // Invoice details data process
                    $services[] = [
                        'invoiceId' => $motherVisa->invoiceId ?? null,
                        'refId' => $visa->id,
                        'refModel' => Visa::class,
                        'dueAmount' => ($visa->quoteAmount - $visa->receivedAmount),
                        'paidAmount' => $visa->receivedAmount,
                        'supplierData' => $visaSupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $visaSupplierProcessedData['supplierLedgerArray'];

                    if ($motherVisa->invoiceId) {
                        // Invoice process
                        $autoInvoiceCreateResponse = InvoiceService::autoInvoiceForRefund($motherVisa->invoice, $services, Yii::$app->user);
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
                    throw new Exception('Visa data loading failed - ' . Helper::processErrorMessages($visa->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Visa added successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Visa and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function updateVisa(array $requestData, ActiveRecord $visa): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $invoice = $visa->invoice;
            $oldQuoteAmount = $visa->quoteAmount;

            // Update Package
            $visa->setAttributes($requestData['Visa']);
            $visa->netProfit = self::calculateNetProfit($visa->quoteAmount, $visa->costOfSale);
            $visa->paymentStatus = InvoiceService::checkAndDetectPaymentStatus($visa->quoteAmount, $visa->receivedAmount);
            if (!$visa->save()) {
                throw new Exception('Visa update failed - ' . Helper::processErrorMessages($visa->getErrors()));
            }

            //Create Package-Supplier Entity
            $suppliers = $requestData['VisaSupplier'];
            if (!$suppliers) {
                throw new Exception('At least 1 Supplier is required');
            }
            $updateVisaSupplierResponse = self::updateVisaSupplier($visa, $suppliers, $invoice);
            if (!$updateVisaSupplierResponse['status']) {
                throw new Exception($updateVisaSupplierResponse['message']);
            }

            if (!empty($invoice) && ($oldQuoteAmount != $visa->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $visa->id,
                    'refModel' => get_class($visa),
                    'due' => ($visa->quoteAmount - $visa->receivedAmount),
                    'amount' => $visa->receivedAmount
                ];

                $updateServiceQuoteResponse = ServiceComponent::updatedServiceRelatedData($visa, $services);
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

    private static function updateVisaSupplier(ActiveRecord $visa, mixed $suppliers, mixed $invoice)
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

    private function visaSupplierProcess(ActiveRecord $visa, mixed $visaSuppliers): array
    {
        $serviceSupplierData = [];
        $supplierLedgerArray = [];
        foreach ($visaSuppliers as $singleSupplierArray) {
            $visaSupplier = new VisaSupplier();
            $visaSupplier->load(['VisaSupplier' => $singleSupplierArray]);
            $visaSupplier->visaId = $visa->id;
            $visaSupplier = $this->visaRepository->store($visaSupplier);
            if ($visaSupplier->hasErrors()) {
                throw new Exception('Visa Supplier refund creation failed - ' . Helper::processErrorMessages($visaSupplier->getErrors()));
            }

            $serviceSupplierData[] = [
                'refId' => $visaSupplier->id,
                'refModel' => VisaSupplier::class,
                'subRefModel' => Invoice::class,
                'dueAmount' => $visaSupplier->costOfSale,
                'paidAmount' => $visaSupplier->paidAmount,
            ];

            // Supplier ledger data process
            if (isset($supplierLedgerArray[$visaSupplier->supplierId])) {
                $supplierLedgerArray[$visaSupplier->supplierId]['credit'] += $visaSupplier->costOfSale;
            } else {
                $supplierLedgerArray[$visaSupplier->supplierId] = [
                    'debit' => 0,
                    'credit' => $visaSupplier->costOfSale,
                    'refId' => $visaSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'subRefId' => null
                ];
            }
        }

        return ['serviceSupplierData' => $serviceSupplierData, 'supplierLedgerArray' => $supplierLedgerArray];
    }
}