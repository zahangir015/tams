<?php

use app\modules\hrm\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Employee'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'userId',
            'reportTo',
            'firstName',
            //'lastName',
            //'fathersName',
            //'mothersName',
            //'dateOfBirth',
            //'gender',
            //'bloodGroup',
            //'maritalStatus',
            //'religion',
            //'nid',
            //'officialId',
            //'officialEmail:email',
            //'officialPhone',
            //'permanentAddress',
            //'presentAddress',
            //'personalEmail:email',
            //'personalPhone',
            //'contactPersonsName',
            //'contactPersonsPhone',
            //'contactPersonsAddress',
            //'contactPersonsRelation',
            //'joiningDate',
            //'confirmationDate',
            //'inProhibition',
            //'jobCategory',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Employee $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
