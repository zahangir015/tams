<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view card card-custom">
    <div class="card-header bg-gray-dark">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="card-body">

        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'uid' => $model->uid], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'shortName',
                'phone',
                'email:email',
                'address',
                [
                    'attribute' => 'logo',
                    'format' => ['image', ['width'=>'200','height'=>'100']],
                    'value' => function ($model) {
                        return '/uploads/company/' . $model->logo;
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
