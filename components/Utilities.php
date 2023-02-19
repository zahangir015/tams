<?php

namespace app\components;

use app\modules\configuration\models\Company;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use Yii;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Utilities
{

    public static function uniqueCode($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    /**
     * @param $name
     * @return bool|string
     */
    public static function alias($name)
    {
        return Yii::getAlias($name);
    }

    public static function convertToTimestamp($dateTime)
    {
        return strtotime($dateTime);
    }

    public static function avatar($name = null, $found = true): string
    {
        if ($found && isset(Yii::$app->user->identity->employeeDetails->profilePicture) && !empty(Yii::$app->user->identity->employeeDetails->profilePicture)) {
            return Yii::$app->user->identity->employeeDetails->profilePicture;
        } else {
            return 'https://ui-avatars.com/api/?name=' . ucfirst(preg_replace("/\s+/", "", $name)) . '&size=128&background=f3f6f9&color=5e6278';
        }
    }

    public static function convertNumber($number): string
    {
        if (is_numeric($number) && strpos($number, '.') === false) {
            $number = number_format((float)$number, 2, '.', '');
        }

        list($integer, $fraction) = explode(".", (string)$number);

        $output = "";

        if ($integer[0] == "-") {
            $output = "negative ";
            $integer = ltrim($integer, "-");
        } else if ($integer[0] == "+") {
            $output = "positive ";
            $integer = ltrim($integer, "+");
        }

        if ($integer[0] == "0") {
            $output .= "zero";
        } else {
            $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
            $group = rtrim(chunk_split($integer, 3, " "), " ");
            $groups = explode(" ", $group);

            $groups2 = array();
            foreach ($groups as $g) {
                $groups2[] = self::convertThreeDigit($g[0], $g[1], $g[2]);
            }

            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != "") {
                    $output .= $groups2[$z] . self::convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11][0] == '0'
                            ? " and "
                            : ", "
                        );
                }
            }

            $output = rtrim($output, ", ");
        }

        if ($fraction > 0) {
            $output .= " point";
            for ($i = 0; $i < strlen($fraction); $i++) {
                $output .= " " . self::convertDigit($fraction[$i]);
            }
        }

        return $output . ' only.';
    }

    public static function convertThreeDigit($digit1, $digit2, $digit3): string
    {
        $buffer = "";

        if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
            return "";
        }

        if ($digit1 != "0") {
            $buffer .= self::convertDigit($digit1) . " hundred";
            if ($digit2 != "0" || $digit3 != "0") {
                $buffer .= " and ";
            }
        }

        if ($digit2 != "0") {
            $buffer .= self::convertTwoDigit($digit2, $digit3);
        } else if ($digit3 != "0") {
            $buffer .= self::convertDigit($digit3);
        }

        return $buffer;
    }

    public static function convertTwoDigit($digit1, $digit2)
    {
        if ($digit2 == "0") {
            switch ($digit1) {
                case "1":
                    return "ten";
                case "2":
                    return "twenty";
                case "3":
                    return "thirty";
                case "4":
                    return "forty";
                case "5":
                    return "fifty";
                case "6":
                    return "sixty";
                case "7":
                    return "seventy";
                case "8":
                    return "eighty";
                case "9":
                    return "ninety";
            }
        } else if ($digit1 == "1") {
            switch ($digit2) {
                case "1":
                    return "eleven";
                case "2":
                    return "twelve";
                case "3":
                    return "thirteen";
                case "4":
                    return "fourteen";
                case "5":
                    return "fifteen";
                case "6":
                    return "sixteen";
                case "7":
                    return "seventeen";
                case "8":
                    return "eighteen";
                case "9":
                    return "nineteen";
            }
        } else {
            $temp = self::convertDigit($digit2);
            switch ($digit1) {
                case "2":
                    return "twenty-$temp";
                case "3":
                    return "thirty-$temp";
                case "4":
                    return "forty-$temp";
                case "5":
                    return "fifty-$temp";
                case "6":
                    return "sixty-$temp";
                case "7":
                    return "seventy-$temp";
                case "8":
                    return "eighty-$temp";
                case "9":
                    return "ninety-$temp";
            }
        }
    }

    public static function convertDigit($digit)
    {
        switch ($digit) {
            case "0":
                return "zero";
            case "1":
                return "one";
            case "2":
                return "two";
            case "3":
                return "three";
            case "4":
                return "four";
            case "5":
                return "five";
            case "6":
                return "six";
            case "7":
                return "seven";
            case "8":
                return "eight";
            case "9":
                return "nine";
        }
    }

    public static function convertGroup($index)
    {
        switch ($index) {
            case 11:
                return " decillion";
            case 10:
                return " nonillion";
            case 9:
                return " octillion";
            case 8:
                return " septillion";
            case 7:
                return " sextillion";
            case 6:
                return " quintrillion";
            case 5:
                return " quadrillion";
            case 4:
                return " trillion";
            case 3:
                return " billion";
            case 2:
                return " million";
            case 1:
                return " thousand";
            case 0:
                return "";
        }
    }

    public static function date($format, $time = null): string
    {
        if ($time == null) {
            $time = "now";
        }
        if (!$format || !is_string($format)) {
            $format = 'Y-m-d';
        }
        $datetime = new \DateTime($time);
        return $datetime->format($format);
    }

    public static function transactionIdGenerate(): string
    {
        return Company::findOne(1)->shortName . '_TR_' . self::date('Ymd_his') . rand(11, 99) . ((php_sapi_name() != "cli") ? Yii::$app->user->id : 1);
    }

    public static function ExpenseNumberGenerate(): string
    {
        return Company::findOne(1)->shortName . 'EXP' . self::date('Ymdhis') . rand(11, 99) . ((php_sapi_name() != "cli") ? Yii::$app->user->id : 1);
    }


    public static function validateFile($file, $type = 'file'): bool
    {
        if (!$file || !file_exists($file->tempName)) {
            return false;
        }
        $maxSize = self::getMaxUploadSize();
        if ($file->size > $maxSize) {
            return false;
        }

        $uploadOptions = self::get('uploadOptions');
        $key = 'allowed' . ucfirst($type) . 'Types';
        $allowedTypes = $uploadOptions[$key];

        switch ($type) {
            case "image":
                return self::validateImageFile($file, $allowedTypes);
                break;
            case "file":
                return self::validateFileTypes($file, $allowedTypes);
                break;
            default:
                return false;
                break;
        }
    }

    protected static function validateImageFile($file, $allowedTypes): bool
    {
        $isValid = true;
        $ext = $file->extension;
        $fileInfo = getimagesize($file->tempName);
        if (!isset($fileInfo[0]) || !is_numeric($fileInfo[0]) || $fileInfo[0] <= 0
            || !isset($fileInfo[0]) || !is_numeric($fileInfo[0]) || $fileInfo[0] <= 0
            || $fileInfo['mime'] !== $allowedTypes[$ext]) {
            $isValid = false;
        }
        return $isValid;
    }

    protected static function validateFileTypes($file, $allowedTypes): bool
    {
        $isValid = true;
        $ext = $file->extension;

        if ($file->error !== UPLOAD_ERR_OK
            || !isset($allowedTypes[$ext])
            || $file->type !== $allowedTypes[$ext]) {
            $isValid = false;
        }

        return $isValid;
    }

    public static function get($key)
    {
        if (!$key) {
            return false;
        }
        if (!isset(Yii::$app->params[$key])) {
            return false;
        }

        return Yii::$app->params[$key];
    }

    public static function checkDir($path)
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                self::checkDir($p);
            }
        } else {
            // check if upload dir exists
            if (!file_exists($path)) {
                mkdir($path, 0777);
            }
        }
    }

    public static function toggleDataOptions(): array
    {
        return [
            'all' => [
                'icon' => 'fas fa-expand-alt',
                'label' => 'All',
                'class' => 'btn btn-secondary',
                'title' => 'Show all data'
            ],
            'page' => [
                'icon' => 'fas fa-compress-alt',
                'label' => 'Page',
                'class' => 'btn btn-secondary',
                'title' => 'Show first page data'
            ],
        ];
    }

    public static function getPanel($title): array
    {
        return [
            'type' => 'light card',
            'heading' => $title,
            'headingOptions' => ['class' => 'card-header'],
            'before' => '<em></em>',
            'footerOptions' => ['class' => 'card-footer', 'style' => 'padding: 1rem 2.25rem !important'],
        ];
    }

    public static function getActiveInactiveButton($url, $model, $key, $pjax = null): string
    {
        return Html::a($model->status == Constant::ACTIVE_STATUS ? '<i class="fas fa-ban text-danger"></i>' : '<i class="fas fa-check-circle text-primary"></i>', ['active-inactive', 'uid' => $model->uid, 'status' => $model->status == Constant::ACTIVE_STATUS ? Constant::INACTIVE_STATUS : Constant::ACTIVE_STATUS], [
                'class' => $model->status == Constant::ACTIVE_STATUS ? 'btn btn-icon btn-light btn-sm btn-hover-danger ' : ' btn btn-icon btn-light btn-sm btn-hover-primary ',
                'role' => 'modal-remote',
                'type' => 'button',
                'title' => $model->status == Constant::ACTIVE_STATUS ? 'Inactive' : 'Active',
                'data-confirm' => false, 'data-method' => false,
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'data-pjax' => $pjax,
                'data-confirm-title' => 'Are you sure?',
                'data-confirm-message' => $model->status == Constant::ACTIVE_STATUS ? 'Are you sure want to Inactive this item?' : 'Are you sure want to Active this item?']
        );

    }

    public static function renderModal($content, $modelSize = 'lg')
    {
        Modal::begin([
            'title' => 'Search',
            'id' => 'filter-search',
            'size' => 'modal-' . $modelSize,
            'options' => [
                'id' => 'filter',
                'tabindex' => false,
            ],
        ]);

        echo $content;

        Modal::end();
    }

    public static function formattingNumber($number)
    {
        if (is_int($number)) {
            return $number;
        }
        return round($number, 2);
    }

    public static function createButton($create = 'create'): string
    {
        return Html::a('<i class="flaticon-add"></i> Create', [$create], ['class' => 'btn btn-light-primary mr-2 font-weight-bolder btn-sm', 'role' => 'modal-remote']);

    }

    public static function processErrorMessages($errors): string
    {
        if (is_array($errors)) {
            $alertMessages = [];
            foreach ($errors as $key => $error) {
                $alertMessages = array_merge($alertMessages, $error);
            }
            return implode(',', $alertMessages);
        } else {
            return $errors;
        }

    }

    public static function imgValidUrl($image, $showDummy = true): string
    {
        if (!$image && !$showDummy) {
            return false;
        }
        if (!$image && $showDummy) {
            return '/dummy.jpg';
        }

        $url = filter_var($image, FILTER_VALIDATE_URL);
        if ($url) {
            return $image;
        } else if (!$url && file_exists(Yii::getAlias('@webroot') . '/uploads/tmp/' . $image)) {
            return '/uploads/tmp/' . $image;
        } else {
            return '/dummy.jpg';
        }
    }

    public static function preventDuplicateAssets()
    {
        Yii::$app->assetManager->bundles = [
            'yii\bootstrap4\BootstrapPluginAsset' => false,
            'yii\bootstrap4\BootstrapAsset' => false,
            'yii\web\JqueryAsset' => false
        ];
    }

    public static function groupMap($data, string $groupByColumn): array
    {
        $result = [];
        foreach ($data as $element) {
            if ($groupByColumn !== null) {
                $result[$element[$groupByColumn]][] = $element;
            }
        }
        return $result;
    }

    public static function dateFormat($disabled = false, $required = false, $format = 'Y-m-d', $value = null): array
    {
        return [
            'options' => [
                'placeholder' => $format,
                'autocomplete' => 'off',
                'required' => $required,
                'class' => 'form-control',
                'disabled' => $disabled ? 'disabled' : false,
                'value' => $value ?? null
            ],
            'convertFormat' => true,
            'pluginOptions' => [
                'singleDatePicker' => true,
                'showDropdowns' => true,
                'endDate' => "0d",
                'locale' => ['format' => $format],
            ],
            'pluginEvents' => [
                'apply.daterangepicker' => 'function(ev, picker) {
                        console.log(picker)
                            if($(this).val() == "") {
                            picker.callback(picker.startDate.clone(), picker.endDate.clone(), picker.chosenLabel);
                            }
                        }']
        ];
    }

    public static function invoiceNumber(): string
    {
        return 'INV' . date('ymdhis') . rand(9, 999);
    }

    public static function refundTransactionNumber(): string
    {
        return 'RFT' . date('ymdhis') . rand(9, 999);
    }

    public static function transactionNumber(): string
    {
        return 'TRN' . date('ymdhis') . rand(9, 999);
    }

    public static function holidayIdentificationNumber(): string
    {
        return 'HLI' . date('ymdhis') . rand(9, 999);
    }

    public static function hotelIdentificationNumber(): string
    {
        return 'HTL' . date('ymdhis') . rand(9, 999);
    }

    public static function visaIdentificationNumber(): string
    {
        return 'VA' . date('ymdhis') . rand(9, 999);
    }

    public static function expenseIdentificationNumber(): string
    {
        return 'EXP' . date('ymdhis') . rand(9, 999);
    }

    public static function getJournalNumber(): string
    {
        return 'JRE' . date('ymdhis') . rand(9, 999);
    }

    public static function serviceTypeLabelClass($value, array $types): string
    {
        if ($value == $types['New']) {
            return 'label-success';
        } elseif ($value == $types['Refund']) {
            return 'label-warning';
        } elseif ($value == $types['Refund Requested']) {
            return 'label-danger';
        } else {
            return 'label-primary';
        }
    }

    public static function servicePaymentStatusLabelClass($value): string
    {
        if ($value == Constant::PAYMENT_STATUS['Due']) {
            return 'label-light-danger';
        } elseif ($value == Constant::PAYMENT_STATUS['Partially Paid']) {
            return 'label-light-warning';
        } else {
            return 'label-light-success';
        }
    }

    public static function visaProcessStatusLabelClass($value): string
    {
        if ($value == Constant::VISA_PROCESS_STATUS['Received']) {
            return 'label-light-primary';
        } elseif (($value == Constant::VISA_PROCESS_STATUS['Passport Delivered - Success'])) {
            return 'label-light-success';
        } elseif (($value == Constant::VISA_PROCESS_STATUS['Passport Delivered - Rejected'])) {
            return 'label-light-danger';
        } else {
            return 'label-light-warning';
        }
    }

    public static function statusLabelClass($value): string
    {
        if ($value) {
            return 'badge-success';
        }
        return 'badge-danger';
    }

    public static function typeLabelClass($value): string
    {
        if ($value) {
            return 'badge-warning';
        }
        return 'badge-primary';
    }

    public static function getServiceName($invoiceDetailRefModel): string
    {
        $splittedRefModel = explode('\\', $invoiceDetailRefModel);
        return $refModel = strtolower(end($splittedRefModel));
    }

    public static function getBasicActionColumnArray(): array
    {
        return [
            'view' => function ($url, $model) {
                return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
            'edit' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'Update'),
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-trash-alt"></i>', ['delete', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'Delete'),
                    'data-pjax' => '0',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
        ];
    }

    public static function getBasicActionColumnWithPayArray(): array
    {
        return [
            'view' => function ($url, $model) {
                return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
            'edit' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'Update'),
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
            'pay' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-credit-card"></i>', ['pay', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'Pay'),
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-trash-alt"></i>', ['delete', 'uid' => $model->uid], [
                    'title' => Yii::t('app', 'Delete'),
                    'data-pjax' => '0',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                    'class' => 'btn btn-primary btn-xs'
                ]);
            },
        ];
    }

    public static function ajaxDropDown($name, $endPoint, $required = false, $id = null, $class = null, array $data = null, $disabled = false): array
    {
        return [
            'name' => $name,
            'options' => [
                'placeholder' => 'Type for suggestion ...',
                'id' => ($id) ?? $name,
                'class' => ($class) ?? $name,
                'required' => $required,
                'disabled' => $disabled ? 'readonly' : false
            ],
            'theme' => Select2::THEME_DEFAULT,
            //'size' => Select2::MEDIUM,
            'data' => $data,
            'maintainOrder' => true,
            'pluginOptions' => [
                //'tags' => true,
                'allowClear' => true,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to($endPoint),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {query:params.term}; }'),
                ],
            ],
        ];
    }

    public static function getWidgetOptions(): array
    {
        return
            ([
                'presetDropdown' => false,
                'convertFormat' => false,
                'pluginOptions' => [
                    'separator' => ' - ',
                    'format' => 'YYYY-MM-DD',
                    'locale' => [
                        'format' => 'YYYY-MM-DD'
                    ],
                ],
            ]);
    }

    public static function getDateRangeWidget(): array
    {
        return [
            'presetDropdown' => false,
            'convertFormat' => false,
            'pluginOptions' => [
                'separator' => ' - ',
                'format' => 'YYYY-MM-DD',
                'locale' => [
                    'format' => 'YYYY-MM-DD'
                ],
            ],
            'pluginEvents' => [
                'apply.daterangepicker' => 'function(ev, picker) {
                        console.log(picker)
                            if($(this).val() == "") {
                            picker.callback(picker.startDate.clone(), picker.endDate.clone(), picker.chosenLabel);
                            }
                        }'],
        ];
    }

    public static function ajaxSelect2Widget($name, $endPoint, $required = false, $id = null, $class = null, array $data = null, $disabled = false): array
    {
        return [
            'name' => $name,
            'options' => [
                'placeholder' => 'Type for suggestion ...',
                'id' => ($id) ?? $name,
                'class' => ($class) ?? $name,
                'required' => $required,
                'disabled' => $disabled ? 'readonly' : false
            ],
            'theme' => Select2::THEME_DEFAULT,
            //'size' => Select2::MEDIUM,
            'data' => $data,
            'maintainOrder' => true,
            'pluginOptions' => [
                //'tags' => true,
                'allowClear' => true,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to($endPoint),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {query:params.term}; }'),
                ],
            ],
        ];
    }

    public static function fileInputWidget(): array
    {
        return [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
            ]
        ];
    }

    public static function depDropConfigurationGenerate($model, $id, $depandedId, $endPoint, $data = []): array
    {
        return [
            'options' => ['id' => $id],
            'data' => $data,
            'pluginOptions' => [
                'depends' => [$depandedId],
                'initialize' => !$model->isNewRecord,
                'placeholder' => 'Select...',
                'url' => Url::to([$endPoint]),
            ]
        ];
    }

    public static function getDateWidgetWithOutActiveForm($name, $row, $id = null, $class = null): array
    {
        return [
            'name' => "[$row]$name",
            'id' => ($id ?? '') . $row,
            'class' => $class ?? '',
            'required' => true,
            'value' => self::date('Y-m-d'),
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ];
    }

    public static function getDateWidget($id, $class = null, $disable = false): array
    {
        return [
            'options' => [
                'id' => $id,
                'class' => $class ?? '',
                'disabled' => $disable,
                'required' => true,
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ];
    }

    public static function getToolbar($create = false, $reset = true, $export = true, $toggleData = true, $filter = true): array
    {
        $create = $create ? html::a('<i class="fas fa-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Create', 'class' => 'btn btn-primary rounded mr-2']) : '';
        $reset = $reset ? Html::a('<i class="fas fa-redo-alt"></i>', [''], ['data-pjax' => 0, 'class' => 'btn btn-light-success rounded mr-1 reloadBtn', 'title' => 'Reset Grid']) : '';
        $filter = $filter ? Html::a('<i class="fas fa-filter"></i>', [''], [
            'type' => 'button',
            'data-toggle' => 'modal',
            'data-target' => '#filter',
            'title' => Yii::t('app', 'Filter'),
            'class' => 'btn btn-default mr-1'
        ]) : '';

        return [
            ['content' => $filter . $create . $reset],
            $export ? '{export}' : '',
            $toggleData ? '{toggleData}' : ''
        ];
    }

    public static function getExport($dataProvider = null, $column = null): array
    {
        if (!$dataProvider || !$column) {
            return [
                'icon' => 'fas fa-file-export',
                'options' => [
                    'class' => 'btn btn-light-primary mr-1',
                ]
            ];
        }

        $customDropdown = [
            'options' => ['tag' => false],
            'linkOptions' => ['class' => 'dropdown-item']
        ];

        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $column,
            'target' => ExportMenu::TARGET_BLANK,
            'batchSize' => 20,
            'asDropdown' => false, // this is important for this case so we just need to get a HTML list
            'dropdownOptions' => [
                'label' => '<i class="fas fa-external-link-alt"></i> Full'
            ],
            'exportConfig' => [ // set styling for your custom dropdown list items
                ExportMenu::FORMAT_CSV => $customDropdown,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_PDF => $customDropdown,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_EXCEL_X => $customDropdown,
            ],
        ]);

        return [
            'icon' => 'fas fa-file-export',
            'options' => [
                'class' => 'btn btn-light-primary mr-1',
            ],
            'itemsAfter' => [
                '<div role="presentation" class="dropdown-divider"></div>',
                '<div class="dropdown-header">Export All Data</div>',
                $fullExportMenu
            ]
        ];
    }

    public static function getExportConfig(): array
    {
        return [
            GridView::CSV => true,
            GridView::PDF => true,
            GridView::EXCEL => true,
        ];
    }

    public static function getRandomName(): string
    {
        return Yii::$app->security->generateRandomString();
    }

    public static function getTagWidget($data): array
    {
        return [
            'theme' => Select2::THEME_BOOTSTRAP,
            'data' => [],
            'options' => ['placeholder' => 'Select Category ...', 'multiple' => true, 'value' => Json::decode($data)],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ' '],
                //'maximumInputLength' => 10
            ],
        ];
    }

}