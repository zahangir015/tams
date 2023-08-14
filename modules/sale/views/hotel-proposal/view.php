<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\HotelProposal $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hotel-proposal-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= Html::encode($this->title) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="lead">Flight Proposal Details</p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Hotel Category</th>
                            <th>Hotel Name</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>City</th>
                        </tr>
                        <tr>
                            <td><?= $model->hotelCategory->name ?></td>
                            <td><?= $model->hotelName ?></td>
                            <td><?= $model->hotelAddress ?></td>
                            <td><?= $model->country ?></td>
                            <td><?= $model->city ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="lead">Amount Details</p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>#Adult</th>
                            <th>#Child</th>
                            <th>Total Price</th>
                            <th>Discount</th>
                            <th>Notes</th>
                        </tr>
                        <tr>
                            <td><?= $model->numberOfAdult ?></td>
                            <td><?= $model->numberOfChild ?></td>
                            <td><?= $model->totalPrice ?></td>
                            <td><?= $model->discount ?></td>
                            <td><?= $model->notes ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="lead">Amount Details</p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>#Adult</th>
                            <th>#Child</th>
                            <th>Total Price</th>
                            <th>Discount</th>
                            <th>Notes</th>
                        </tr>
                        <tr>
                            <td><?= $model->numberOfAdult ?></td>
                            <td><?= $model->numberOfChild ?></td>
                            <td><?= $model->totalPrice ?></td>
                            <td><?= $model->discount ?></td>
                            <td><?= $model->notes ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'hotelCategoryId',
            'hotelName',
            'hotelAddress',
            'countryId',
            'cityId',
            'numberOfAdult',
            'numberOfChild',
            'amenities:ntext',
            'totalPrice',
            'discount',
            'notes:ntext',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>

</div>
