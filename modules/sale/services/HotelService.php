<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelCategory;
use app\modules\sale\models\hotel\HotelRefund;
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
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->hotelRepository = new HotelRepository();
        $this->invoiceService = new InvoiceService();
    }

    private static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public function findHotel(string $uid, $withArray = []): ActiveRecord
    {
        return $this->hotelRepository->findOne($uid, Hotel::class, $withArray);
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
                        throw new Exception('Hotel create failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
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
                    $autoInvoiceCreateResponse = $this->invoiceService->autoInvoice($customer->id, $services, 1, Yii::$app->user);
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
                    throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
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
                $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
                $hotel = new Hotel();
                if ($hotel->load($requestData)) {
                    $hotel->customerCategory = $customer->category;
                    $hotel->invoiceId = $motherHotel->invoiceId;
                    $hotel = $this->hotelRepository->store($hotel);
                    if ($hotel->hasErrors()) {
                        throw new Exception('Hotel refund creation failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
                    }

                    // Mother Hotel update
                    $motherHotel->type = ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund Requested'];
                    $motherHotel->refundRequestDate = $hotel->refundRequestDate;
                    $motherHotel = $this->hotelRepository->store($motherHotel);
                    if ($motherHotel->hasErrors()) {
                        throw new Exception('Mother hotel update failed - ' . Utilities::processErrorMessages($motherHotel->getErrors()));
                    }

                    // Hotel Supplier data process
                    $hotelSupplierProcessedData = self::hotelSupplierProcess($hotel, $requestData['HotelSupplier']);

                    // Create refund for customer and supplier
                    $refundDataProcessResponse = self::processRefundModelData($hotel, $requestData);
                    if ($refundDataProcessResponse['error']) {
                        throw new Exception('Hotel refund creation failed - ' . $refundDataProcessResponse['message']);
                    }

                    // Invoice details data process
                    $service = [
                        'invoiceId' => $motherHotel->invoiceId ?? null,
                        'refId' => $hotel->id,
                        'refModel' => Hotel::class,
                        'dueAmount' => ($hotel->quoteAmount - $hotel->receivedAmount),
                        'paidAmount' => $hotel->receivedAmount,
                        'motherId' => $motherHotel->id,
                        'supplierData' => $hotelSupplierProcessedData['serviceSupplierData']
                    ];
                    $supplierLedgerArray = $hotelSupplierProcessedData['supplierLedgerArray'];

                    if ($motherHotel->invoiceId) {
                        // Invoice process
                        $autoInvoiceCreateResponse = $this->invoiceService->autoInvoiceForRefund($motherHotel->invoice, $service, Yii::$app->user);
                        if ($autoInvoiceCreateResponse['error']) {
                            throw new Exception('Auto Invoice detail creation failed - ' . $autoInvoiceCreateResponse['message']);
                        }
                    }

                    // Supplier Ledger process
                    /*$ledgerRequestResponse = LedgerService::batchInsert($invoice, $supplierLedgerArray);
                    if ($ledgerRequestResponse['error']) {
                        throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                    }*/
                    $dbTransaction->commit();
                    Yii::$app->session->setFlash('success', 'Hotel added successfully');
                    return true;
                } else {
                    throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
                }
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
                throw new Exception('Hotel update failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
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
            $hotelSupplierProcessedData = self::updateHotelSupplier($hotel, $requestData['HotelSupplier'],);
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
                throw new Exception('Hotel Supplier refund creation failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors()));
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

    private function processRefundModelData(ActiveRecord $hotel, array $requestData): array
    {
        $referenceData = [
            [
                'refId' => $hotel->customerId,
                'refModel' => Customer::class,
                'serviceCharge' => $hotel->quoteAmount,
                'hotelId' => $hotel->id,
                'refundRequestDate' => $hotel->refundRequestDate,
                'isRefunded' => 0,
            ],
        ];

        foreach ($hotel->hotelSuppliers as $singleSupplier) {
            if ($singleSupplier->type == ServiceConstant::SERVICE_TYPE_FOR_CREATE['Refund']) {
                $referenceData[] = [
                    'refId' => $singleSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'serviceCharge' => $singleSupplier->costOfSale,
                    'hotelId' => $singleSupplier->id,
                    'refundRequestDate' => $hotel->refundRequestDate,
                    'isRefunded' => 0,
                ];
            }
        }

        $hotelRefundBatchData = [];
        // Customer Hotel refund data process
        foreach ($referenceData as $ref) {
            $hotelRefund = new HotelRefund();
            $hotelRefund->load($requestData);
            $hotelRefund->load(['HotelRefund' => $ref]);
            if (!$hotelRefund->validate()) {
                return ['error' => true, 'message' => 'Hotel Refund validation failed - ' . Utilities::processErrorMessages($hotelRefund->getErrors())];
            }
            $hotelRefundBatchData[] = $hotelRefund->getAttributes(null, ['id']);
        }

        // Hotel Refund batch insert process
        if (empty($hotelRefundBatchData)) {
            return ['error' => true, 'message' => 'Hotel Refund batch data process failed.'];
        }

        if (!$this->hotelRepository->batchStore('hotel_refund', array_keys($hotelRefundBatchData[0]), $hotelRefundBatchData)) {
            return ['error' => true, 'message' => 'Hotel Refund batch insert failed'];
        }

        return ['error' => false, 'message' => 'Hotel Refund process done.'];
    }
}