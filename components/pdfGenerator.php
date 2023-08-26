<?php

namespace app\components;

use Dompdf\Dompdf;
use Dompdf\Options;
use Yii;

class pdfGenerator
{
    public static function makeInvoice($data, $fileName): void
    {
        /*header('Content-type: application/pdf');
        header('Content-Disposition: attachment;');*/
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_ENABLE_CSS_FLOAT', true);
        $pdfTemplate = '@app/modules/account/pdf/' . $fileName;

        $html = Yii::$app->view->render($pdfTemplate, $data);

        $dompdf = new Dompdf();
        $assets = Yii::getAlias('@app') . "/pdf/assets/";
        $dompdf->setBasePath($assets);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $html = preg_replace('/>\s+</', "><", $html);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($fileName, array('Attachment' => 1));
    }
}