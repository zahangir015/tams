<?php

namespace app\components;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class WidgetHelper
{
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