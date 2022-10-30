<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelCategory;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HotelRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class HotelService
{
    private HotelRepository $hotelRepository;

    public function __construct()
    {
        $this->hotelRepository = new HotelRepository();
    }

    private static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public function findHotel(string $uid, $withArray = []): ActiveRecord
    {
        return $this->hotelRepository->findOne($uid, $withArray);
    }

    public function storeHotel(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Hotel']) || !empty($requestData['HotelSupplier'])) {
                $services = [];
                $supplierLedgerArray = [];
                $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
                $hotel = new Hotel();
                if ($hotel->load($requestData)) {
                    $hotel->type = ServiceConstant::TYPE['New'];
                    $hotel->customerCategory = $customer->category;
                    $hotel = $this->hotelRepository->store($hotel);
                    if ($hotel->hasErrors()) {
                        throw new Exception('Hotel create failed - ' . Helper::processErrorMessages($hotel->getErrors()));
                    }

                    // Hotel Supplier data process
                    $hotelSupplierProcessedData = self::hotelSupplierProcess($hotel, $requestData['HotelSupplier']);

                    // Invoice details data process
                    $services[] = [
                        'refId' => $hotel->id,
                        'refModel' => Hotel::class,
                        'dueAmount' => $hotel->quoteAmount,
                        'paidAmount' => 0,
                        'supplierData' => $hotelSupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $hotelSupplierProcessedData['supplierLedgerArray'];

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
                    throw new Exception('Hotel data loading failed - ' . Helper::processErrorMessages($hotel->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Hotel added successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Hotel and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function refundHotel(array $requestData, ActiveRecord $motherHotel): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Hotel']) || !empty($requestData['HotelSupplier'])) {
                $services = [];
                $invoice = null;
                $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
                $hotel = new Hotel();
                if ($hotel->load($requestData)) {
                    $hotel->customerCategory = $customer->category;
                    $hotel = $this->hotelRepository->store($hotel);
                    if ($hotel->hasErrors()) {
                        throw new Exception('Hotel refund create failed - ' . Helper::processErrorMessages($hotel->getErrors()));
                    }

                    // Hotel Supplier data process
                    $hotelSupplierProcessedData = self::hotelSupplierProcess($hotel, $requestData['HotelSupplier']);

                    // Invoice details data process
                    $services[] = [
                        'invoiceId' => $motherHotel->invoiceId ?? null,
                        'refId' => $hotel->id,
                        'refModel' => Hotel::class,
                        'dueAmount' => ($hotel->quoteAmount - $hotel->receivedAmount),
                        'paidAmount' => $hotel->receivedAmount,
                        'supplierData' => $hotelSupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $hotelSupplierProcessedData['supplierLedgerArray'];

                    if ($motherHotel->invoiceId) {
                        // Invoice process
                        $autoInvoiceCreateResponse = InvoiceService::autoInvoiceForRefund($motherHotel->invoice, $services, Yii::$app->user);
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
                    throw new Exception('Hotel data loading failed - ' . Helper::processErrorMessages($hotel->getErrors()));
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Hotel added successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Hotel and supplier data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function updateHotel(array $requestData, ActiveRecord $hotel): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (empty($requestData['Hotel']) || !empty($requestData['HotelSupplier'])) {
                throw new Exception('Hotel and supplier data can not be empty.');
            }

            $oldQuoteAmount = $hotel->quoteAmount;
            $oldReceivedAmount = $hotel->receivedAmount;
            $oldCustomerId = $hotel->customerId;

            // Update Hotel Model
            $hotel->load($requestData);
            $hotel->netProfit = self::calculateNetProfit($hotel->quoteAmount, $hotel->costOfSale);
            $hotel->paymentStatus = InvoiceService::checkAndDetectPaymentStatus($hotel->quoteAmount, $hotel->receivedAmount);
            if (!$hotel->save()) {
                throw new Exception('Hotel update failed - ' . Helper::processErrorMessages($hotel->getErrors()));
            }

            // TODO Invoice update
            $quoteAmountDiff = ($hotel->quoteAmount - $oldQuoteAmount);
            if ($hotel->invoiceId && $quoteAmountDiff) {
                $invoiceUpdateResponse = InvoiceService::updateInvoice($hotel->invoice, $hotel, $quoteAmountDiff);
            }
            // TODO Customer ledger update
            // TODO Service Payment timeline update


            // Update Hotel Supplier
            // TODO Supplier ledger update
            // TODO Bill update
            $hotelSupplierProcessedData = self::updateHotelSupplier($hotel, $requestData['HotelSupplier'], );
            // Invoice details data process
            $services[] = [
                'invoiceId' => $motherHotel->invoiceId ?? null,
                'refId' => $hotel->id,
                'refModel' => Hotel::class,
                'dueAmount' => ($hotel->quoteAmount - $hotel->receivedAmount),
                'paidAmount' => $hotel->receivedAmount,
                'supplierData' => $hotelSupplierProcessedData['serviceSupplierData']
            ];
            $supplierLedgerArray = $hotelSupplierProcessedData['supplierLedgerArray'];


            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Package has been updated successfully');
            return true;
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }
    }

    private static function updateHotelSupplier(ActiveRecord $hotel, mixed $suppliers, mixed $invoice)
    {
        $selectedPackageSuppliers = [];
        $deletedSuppliers = [];
        $suppliersLedgerData = [];

        foreach ($suppliers as $supplier) {
            $checkSupplier = Supplier::findOne(['id' => $supplier['supplierId']]);
            if (!$checkSupplier)
                return ['status' => false, 'message' => 'Supplier not found'];

            if (!empty($packageSupplier['id'])) {
                $model = HotelSupplier::findOne(['id' => $packageSupplier['id']]);
                $selectedPackageSuppliers[] = $model->id;
            } else {
                $model = new HotelSupplier();
                $model->packageId = $hotel->id;
                $model->identificationNo = $hotel->identificationNumber;
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

    private function hotelSupplierProcess(ActiveRecord $hotel, mixed $hotelSuppliers): array
    {
        $serviceSupplierData = [];
        $supplierLedgerArray = [];
        foreach ($hotelSuppliers as $singleSupplierArray) {
            $hotelSupplier = new HotelSupplier();
            $hotelSupplier->load(['HotelSupplier' => $singleSupplierArray]);
            $hotelSupplier->hotelId = $hotel->id;
            $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
            if ($hotelSupplier->hasErrors()) {
                throw new Exception('Hotel Supplier refund creation failed - ' . Helper::processErrorMessages($hotelSupplier->getErrors()));
            }

            $serviceSupplierData[] = [
                'refId' => $hotelSupplier->id,
                'refModel' => HotelSupplier::class,
                'subRefModel' => Invoice::class,
                'dueAmount' => $hotelSupplier->costOfSale,
                'paidAmount' => $hotelSupplier->paidAmount,
            ];

            // Supplier ledger data process
            if (isset($supplierLedgerArray[$hotelSupplier->supplierId])) {
                $supplierLedgerArray[$hotelSupplier->supplierId]['credit'] += $hotelSupplier->costOfSale;
            } else {
                $supplierLedgerArray[$hotelSupplier->supplierId] = [
                    'debit' => 0,
                    'credit' => $hotelSupplier->costOfSale,
                    'refId' => $hotelSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'subRefId' => null
                ];
            }
        }

        return ['serviceSupplierData' => $serviceSupplierData, 'supplierLedgerArray' => $supplierLedgerArray];
    }
}