<?php

namespace app\components;

use Dompdf\Dompdf;
use Dompdf\Exception;
use Dompdf\Options;
use Yii;

class pdfGenerator
{
    public static function makeInvoice($data, $fileName): array
    {
        try {
            header("Content-Type: application/pdf");
            header('Content-Disposition: attachment; filename="invoice.pdf"');
            define('DOMPDF_ENABLE_AUTOLOAD', true);
            define('DOMPDF_ENABLE_CSS_FLOAT', true);
            define("DOMPDF_AUTOLOAD_PREPEND", true);
            $pdfTemplate = '@app/mail/pdf/' . $fileName;

            $html = Yii::$app->view->render($pdfTemplate, $data);

            $dompdf = new Dompdf();
            $assets = Yii::getAlias('@app') . "/pdf/assets/";
            $dompdf->setBasePath($assets);

            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $options->isRemoteEnabled();
            $dompdf->setOptions($options);

            $html = preg_replace('/>\s+</', "><", $html);
            $dompdf->loadHtml(utf8_decode($html), 'UTF-8');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream($fileName, array('Attachment' => 0));
            exit;
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }

    }

    public static function makeMoneyReceipt($data, $fileName): array
    {
        try {
            header("Content-Type: application/pdf");
            header('Content-Disposition: attachment; filename="invoice.pdf"');
            define('DOMPDF_ENABLE_AUTOLOAD', true);
            define('DOMPDF_ENABLE_CSS_FLOAT', true);
            define("DOMPDF_AUTOLOAD_PREPEND", true);
            $pdfTemplate = '@app/mail/pdf/' . $fileName;

            $html = Yii::$app->view->render($pdfTemplate, $data);

            $dompdf = new Dompdf();
            $assets = Yii::getAlias('@app') . "/pdf/assets/";
            $dompdf->setBasePath($assets);

            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $options->isRemoteEnabled();
            $dompdf->setOptions($options);

            $html = preg_replace('/>\s+</', "><", $html);
            $dompdf->loadHtml(utf8_decode($html), 'UTF-8');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream($fileName, array('Attachment' => 0));
            exit;
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }

    }
}