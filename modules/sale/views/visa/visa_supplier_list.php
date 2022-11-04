<?php

use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\visa\VisaSupplier;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\visa\VisaSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Visa Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-supplier-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'motherId',
            [
                'attribute' => 'visa',
                'value' => function ($model) {
                    return $model->visa->identificationNumber;
                },
                'label' => 'Visa',
            ],
            [
                'attribute' => 'bill',
                'value' => function ($model) {
                    return $model->bill ? $model->bill->billNumber : null;
                },
                'label' => 'Bill',
            ],
            [
                'attribute' => 'country',
                'value' => function ($model) {
                    return $model->country->name.'('.$model->country->code.')';
                },
                'label' => 'Country',
            ],
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return $model->supplier->company;
                },
                'label' => 'Supplier',
            ],
            'supplierRef',
            'paxName',
            [
                'attribute' => 'issueDate',
                'label' => 'Issue',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'issueDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'refundRequestDate',
                'label' => 'Refund Request',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'refundRequestDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'type',
                'value' => 'type',
                'filter' => ServiceConstant::ALL_VISA_TYPE
            ],
            'serviceDetails',
            'quantity',
            'unitPrice',
            [
                'attribute' => 'costOfSale',
                'label' => 'Cost',
            ],
            [
                'attribute' => 'paidAmount',
                'label' => 'Paid',
            ],
            'securityDeposit',
            'paymentStatus',
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/visa/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/visa/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'responsiveWrap' => false,
        'hover' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
