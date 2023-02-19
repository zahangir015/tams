<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\BankAccount */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-account-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    //'uid',
                    'name',
                    'shortName',
                    'accountName',
                    'accountNumber',
                    'branch',
                    'routingNumber',
                    'swiftCode',
                    'code',
                    'paymentCharge',
                    'logo',
                    'tag',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = \app\components\Utilities::statusLabelClass($model->status);
                            $labelText = ($model->status) ? 'Active' : 'Inactive';
                            return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                        },
                        'format' => 'html'
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
