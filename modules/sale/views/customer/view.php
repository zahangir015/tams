<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'uid' => $model->uid], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'company',
                    'customerCode',
                    'category',
                    'email:email',
                    'address',
                    'phone',
                    [
                        'attribute' => 'creditModality',
                        'value' => function ($model) {
                            $labelClass = Utilities::typeLabelClass($model->creditModality);
                            return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::YES_NO[$model->creditModality] . '</span>';
                        },
                        'format' => 'html',
                    ],
                    'passportNumber',
                    'issuedCountry',
                    'dateOfBirth',
                    'gender',
                    'expireDate',
                    'firstName',
                    'lastName',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = Utilities::statusLabelClass($model->status);
                            return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
                        },
                        'format' => 'html',
                    ],
                    'createdBy',
                    'createdAt',
                    'updatedBy',
                    'updatedAt',
                ],
            ]) ?>
        </div>
    </div>

</div>
