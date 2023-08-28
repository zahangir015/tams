<?php

namespace app\components;

use Dompdf\Dompdf;
use Dompdf\Options;
use kartik\mpdf\Pdf;
use Yii;

class pdfGenerator
{
    /*public static function makeInvoice($data, $fileName)
    {
        header("Content-Type: application/pdf");
        header('Content-Disposition: attachment; filename="invoice.pdf"');
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_ENABLE_CSS_FLOAT', true);
        $pdfTemplate = '@app/mail/pdf/' . $fileName;

        $html = Yii::$app->view->render($pdfTemplate, $data);

        $dompdf = new Dompdf();
        $assets = Yii::getAlias('@app') . "/pdf/assets/";
        $dompdf->setBasePath($assets);

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);

        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($fileName, ['attachment' => 1]);
        exit();
    }*/

    public static function makeinvoice($data, $fileName){
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial($fileName);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Invoice - '.$data['invoice']->invoiceNumber],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}