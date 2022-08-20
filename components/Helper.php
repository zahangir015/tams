<?php

namespace app\components;

use app\modules\configuration\models\Company;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use Yii;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\helpers\Url;
use yii\web\JsExpression;

class Helper
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

    /*
    * number to word convert
    */

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

    /**
     * returns date &| time in given format
     *
     * @param $format
     * @param null $time
     * @return string
     */
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

    public static function getDateRangeWidgetOptions(): array
    {
        return
            [
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

    public static function ajaxDropDownWithModel($model, $name, $endPoint, $required = false, $id = null, $class = null, $data = null, $disabled = null): array
    {
        return [
            'model' => $model,
            'attribute' => $name,
            'options' => [
                'placeholder' => 'Type for suggestion ...',
                'class' => $class ?? '',
                'id' => $id ?? '',
                'required' => $required,
                'disabled' => $disabled ? 'readonly' : false,
            ],
            'theme' => Select2::THEME_DEFAULT,
            'data' => $data ?: [],
            'maintainOrder' => true,
            'pluginOptions' => [
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

    public static function depDropConfigurationGenerate($model, $id, $depandedId, $endPoint): array
    {
        return [
            'options' => ['id' => $id],
            'data' => ($model->isNewRecord) ? [] : [$model->departmentId => $model->department->name],
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

    /**
     * @return string
     */
    public static function getRandomName(): string
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * @return mixed
     */
    public static function getMaxUploadSize()
    {
        $uploadOptions = self::get('uploadOptions');
        return $uploadOptions['maxFileSize'];
    }

    /**
     * @param $file
     * @param string $type
     * @return bool
     */
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

    /**
     * @param $file
     * @param $allowedTypes
     * @return bool
     */
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

    /**
     * @param $file
     * @param $allowedTypes
     * @return bool
     */
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

    /**
     * a short hand method to get params value
     *
     * @param $key
     * @return mixed
     */
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

    /**
     * @param $path
     */
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

    public static function svgViewIcon(): string
    {
        return '<span class="svg-icon svg-icon-md svg-icon-primary">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"></path>
                            <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"></path>
                        </g>
                    </svg>
                </span>';
    }

    public static function svgPreviewIcon(): string
    {
        return '<span class="svg-icon svg-icon-primary svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <polygon fill="#000000" opacity="0.3" points="5 7 5 15 19 15 19 7"/>
                            <path d="M11,19 L11,16 C11,15.4477153 11.4477153,15 12,15 C12.5522847,15 13,15.4477153 13,16 L13,19 L14.5,19 C14.7761424,19 15,19.2238576 15,19.5 C15,19.7761424 14.7761424,20 14.5,20 L9.5,20 C9.22385763,20 9,19.7761424 9,19.5 C9,19.2238576 9.22385763,19 9.5,19 L11,19 Z" fill="#000000" opacity="0.3"/>
                            <path d="M5,7 L5,15 L19,15 L19,7 L5,7 Z M5.25,5 L18.75,5 C19.9926407,5 21,5.8954305 21,7 L21,15 C21,16.1045695 19.9926407,17 18.75,17 L5.25,17 C4.00735931,17 3,16.1045695 3,15 L3,7 C3,5.8954305 4.00735931,5 5.25,5 Z" fill="#000000" fill-rule="nonzero"/>
                        </g>
                    </svg>
                </span>';
    }

    public static function svgEditIcon(): string
    {
        return '<span class="svg-icon svg-icon-md svg-icon-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"></path>
                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                        </g>
                    </svg>
                </span>';
    }

    public static function svgDeleteIcon(): string
    {
        return '<span class="svg-icon svg-icon-primary svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                        <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg>
                </span>';
    }

    public static function svgSaveIcon(): string
    {
        return '<span class="svg-icon svg-icon-primary svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M17,4 L6,4 C4.79111111,4 4,4.7 4,6 L4,18 C4,19.3 4.79111111,20 6,20 L18,20 C19.2,20 20,19.3 20,18 L20,7.20710678 C20,7.07449854 19.9473216,6.94732158 19.8535534,6.85355339 L17,4 Z M17,11 L7,11 L7,4 L17,4 L17,11 Z" fill="#000000" fill-rule="nonzero"/>
                        <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="5" rx="0.5"/>
                        </g>
                    </svg>
                </span>';
    }


    public static function svgRefundIcon(): string
    {
        return '<span class="svg-icon svg-icon-primary svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M8.43296491,7.17429118 L9.40782327,7.85689436 C9.49616631,7.91875282 9.56214077,8.00751728 9.5959027,8.10994332 C9.68235021,8.37220548 9.53982427,8.65489052 9.27756211,8.74133803 L5.89079566,9.85769242 C5.84469033,9.87288977 5.79661753,9.8812917 5.74809064,9.88263369 C5.4720538,9.8902674 5.24209339,9.67268366 5.23445968,9.39664682 L5.13610134,5.83998177 C5.13313425,5.73269078 5.16477113,5.62729274 5.22633424,5.53937151 C5.384723,5.31316892 5.69649589,5.25819495 5.92269848,5.4165837 L6.72910242,5.98123382 C8.16546398,4.72182424 10.0239806,4 12,4 C16.418278,4 20,7.581722 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 L6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,8.6862915 15.3137085,6 12,6 C10.6885336,6 9.44767246,6.42282109 8.43296491,7.17429118 Z" fill="#000000" fill-rule="nonzero"/>
                        </g>
                    </svg>
                </span>';
    }

    public static function checkRolePermission(): bool
    {
        $userRoleName = \Yii::$app->user->identity->getRelatedRole()->one()->name;
        if (in_array($userRoleName, [Constant::SUPER_ADMIN_ROLE_NAME, Constant::HR_MANAGER_ROLE_NAME])) {
            return true;
        }

        return false;
    }

    public static function checkHrAssistantPermission()
    {
        $userRoleName = \Yii::$app->user->identity->getRelatedRole()->one()->name;
        if (in_array($userRoleName, [Constant::HR_ASSISTANT_ROLE_NAME])) {
            return true;
        }

        return false;
    }

    public static function checkSuperAdmin()
    {
        $userRoleName = \Yii::$app->user->identity->getRelatedRole()->one()->name;
        if (in_array($userRoleName, [Constant::SUPER_ADMIN_ROLE_NAME])) {
            return true;
        }
        return false;
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

    public static function dateFormat($disabled = false, $required = false, $format = 'Y-m-d'): array
    {
        return [
            'options' => ['placeholder' => $format, 'autocomplete' => 'off', 'required' => $required, 'class' => 'form-control', 'disabled' => $disabled ? 'disabled' : false],
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
        return Yii::$app->user->id . date('Ymdhis');
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

    public static function LabelClass($value): string
    {
        if ($value) {
            return 'badge-success';
        }
        return 'badge-danger';
    }

    public static function getServiceName($invoiceDetailRefModel): string
    {
        $splittedRefModel = explode('\\', $invoiceDetailRefModel);
        return $refModel = strtolower(end($splittedRefModel));
    }

}