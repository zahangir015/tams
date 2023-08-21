<?php

namespace app\components;

use Dompdf\Dompdf;
use Dompdf\Options;
use Yii;

class pdfGenerator
{
    public static function makeMoneyReceipt($data, $fileName)
    {
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
        $dompdf->render();
        $dompdf->stream($fileName, array('Attachment' => 1));
    }

    public static function makeInvoice($data, $fileName)
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_ENABLE_CSS_FLOAT', true);
        $pdfTemplate = '@app/modules/account/pdf/' . $fileName;

        $html = Yii::$app->view->render($pdfTemplate, $data);

        $dompdf = new Dompdf();
        $assets = Yii::getAlias('@app') . "/pdf/assets/";
        $dompdf->setBasePath($assets);

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);

        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream($fileName, array('Attachment' => 1));
    }

    public static function attachMoneyReceipt($data, $fileName)
    {
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
        $dompdf->render();
        $pdfContent = $dompdf->output();

        $filename = 'money-receipt' . uniqid() . '.pdf';
        $path = Utils::alias('@app') . '/mail/pdf/tmp';

        Utils::checkDir($path);
        $filePath = $path . '/' . $filename;

        file_put_contents($filePath, $pdfContent);
        return $filePath;
    }

    public static function makeVoucher($data, $fileName)
    {
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
        $dompdf->render();
        $dompdf->stream($fileName, array('Attachment' => 1));
    }

    public static function visaProposal($data,$fileName)
    {
        //define('DOMPDF_ENABLE_AUTOLOAD', false);
        //define('DOMPDF_ENABLE_CSS_FLOAT', true);
        $pdfTemplate = '@app/mail/pdf/'.$fileName;
        $html = Yii::$app->view->render($pdfTemplate, $data);

        $dompdf = new Dompdf();
        $assets = Yii::getAlias('@app') . "/pdf/assets/";
        $dompdf->set_base_path($assets);
        //$dompdf->load_html($html);
        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->load_html($html);
        $dompdf->render();
        $pdfContent = $dompdf->output();

        $filename = 'Visa-requirements' . uniqid() . '.pdf';
        $path = Utils::alias('@app').'/mail/pdf/tmp';

        Utils::checkDir($path);
        $filePath = $path .'/'. $filename;

        file_put_contents($filePath, $pdfContent);
        return $filePath;
    }
}