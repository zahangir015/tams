<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\models\InvoiceDetail;
use app\modules\account\repositories\InvoiceRepository;
use yii\db\Exception;

class InvoiceService
{
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
    }

    public static function autoInvoice(int $customerId, array $services, int $group, mixed $user): array
    {
        $invoice = new Invoice();
        $invoice->date = date('Y-m-d');
        $invoice->expectedPaymentDate = $invoice->date;
        $invoice->customerId = $customerId;
        $invoice->invoiceNumber = Helper::invoiceNumber();
        $invoice->due = array_sum(array_column($services, 'due'));;
        $invoice->amount = 0;
        $invoice->comment = 'Auto generated invoice';
        $invoice->remarks = 'Auto generated invoice';
        $invoice->discount = 0;
        $invoice->status = GlobalConstant::ACTIVE_STATUS;

        // Invoice data process
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            throw new Exception('Invoice creation failed - ' . Helper::processErrorMessages($invoice->getErrors()));
        }

        // Invoice Details data process
        $invoiceDetailProcessResponse = self::storeInvoiceDetail($invoice, $services);
        if ($invoiceDetailProcessResponse['error']) {
            return ['error' => true, 'message' => 'Service Data process - ' . $invoiceDetailProcessResponse['message']];
        }

        // Invoice Details data process
        $invoiceDetailProcessResponse = self::storeInvoiceDetail($invoice, $services);
        if ($invoiceDetailProcessResponse['error']) {
            return ['error' => true, 'message' => 'Service Data process - ' . $invoiceDetailProcessResponse['message']];
        }


        if ($invoice->validate() && $invoice->save()) {


            // Customer Ledger process
            $ledgerRequestData = [
                'title' => 'Service Purchase',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $invoice->customerId,
                'refModel' => Customer::className(),
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::className(),
                'debit' => array_sum(array_column($services, 'due')),
                'credit' => 0
            ];
            $ledgerRequestResponse = LedgerComponent::createNewLedger($ledgerRequestData);
            if ($ledgerRequestResponse['error']) {
                return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
            }

            return ['error' => false, 'message' => 'Invoice created successfully', 'data' => $invoice];
        }

        return ['error' => true, 'message' => Utils::processErrorMessages($invoice->getErrors())];
    }

    private static function storeInvoiceDetail(Invoice $invoice, array $services): array
    {
        $batchData = [];
        foreach ($services as $singleService) {
            $invoiceDetail = new InvoiceDetail();
            if (!$invoiceDetail->load(['InvoiceDetail' => $singleService]) || !$invoiceDetail->validate()) {
                return ['error' => true, 'message' => 'Invoice Details validation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
            }
            $invoiceDetail->invoiceId = $invoice->id;
            $batchData[] = $invoiceDetail->getAttributes();
        }

        if (!$this->invoiceRepository->batchStore(InvoiceDetail::tableName(), array_keys($batchData[0]), $batchData)) {
            return ['error' => true, 'message' => 'Invoice Details batch insert failed')];
        }

        return ['error' => false, 'message' => 'Invoice details created successfully'];
    }
}