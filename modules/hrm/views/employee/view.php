<?php

use app\modules\hrm\components\HrmConstant;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */

$this->title = $model->firstName.' '.$model->lastName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'uid' => $model->uid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'uid' => $model->uid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'uid',
            //'userId',
            'reportTo',
            'firstName',
            'lastName',
            'fathersName',
            'mothersName',
            'dateOfBirth:date',
            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    return HrmConstant::GENDER[$model->gender];
                },
            ],
            [
                'attribute' => 'bloodGroup',
                'value' => function ($model) {
                    return HrmConstant::BLOOD_GROUP[$model->bloodGroup];
                },
            ],
            [
                'attribute' => 'maritalStatus',
                'value' => function ($model) {
                    return HrmConstant::MARITAL_STATUS[$model->maritalStatus];
                },
            ],
            [
                'attribute' => 'religion',
                'value' => function ($model) {
                    return HrmConstant::RELIGION[$model->religion];
                },
            ],
            'nid',
            'officialId',
            'officialEmail:email',
            'officialPhone',
            'permanentAddress',
            'presentAddress',
            'personalEmail:email',
            'personalPhone',
            'contactPersonsName',
            'contactPersonsPhone',
            'contactPersonsAddress',
            'contactPersonsRelation',
            'joiningDate:date',
            'confirmationDate:date',
            [
                'attribute' => 'inProhibition',
                'value' => function ($model) {
                    return HrmConstant::PROBATION[$model->inProhibition];
                },
            ],
            'jobCategory',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
        ],
    ]) ?>

</div>
