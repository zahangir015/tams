<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Flight Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="flight-proposal-view">
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
                            <th>Airline</th>
                            <th>Class</th>
                            <th>Trip Type</th>
                            <th>Route</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                        </tr>
                        <tr>
                            <td><?= $model->airline->name . '(' . $model->airline->code . ')' ?></td>
                            <td><?= $model->class ?></td>
                            <td><?= $model->tripType ?></td>
                            <td><?= $model->route ?></td>
                            <td><?= $model->departure ?></td>
                            <td><?= $model->arrival ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="lead">Passenger Details</p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>#Adult</th>
                            <th>Price Per Adult</th>
                            <th>Baggage Per Adult</th>
                            <th>#Child</th>
                            <th>Price Per Child</th>
                            <th>Baggage Per Child</th>
                            <th>#Infant</th>
                            <th>Price Per Infant</th>
                            <th>Baggage Per Infant</th>
                        </tr>
                        <tr>
                            <td><?= $model->numberOfAdult ?></td>
                            <td><?= $model->pricePerAdult ?></td>
                            <td><?= $model->baggagePerAdult ?></td>
                            <td><?= $model->numberOfChild ?></td>
                            <td><?= $model->pricePerChild ?></td>
                            <td><?= $model->baggagePerChild ?></td>
                            <td><?= $model->numberOfInfant ?></td>
                            <td><?= $model->pricePerInfant ?></td>
                            <td><?= $model->baggagePerInfant ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="lead">Payment Details</p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Total Amount</th>
                            <th>Discount</th>
                            <th>Notes</th>
                        </tr>
                        <tr>
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
                            <th>Flight Number</th>
                            <th>Departure From</th>
                            <th>Departure Date</th>
                            <th>Arrival To</th>
                            <th>Arrival Date</th>
                        </tr>
                        <?php
                        foreach ($model->flightProposalItineraries as $itinerary) {
                            ?>
                            <tr>
                                <td><?= $itinerary->flightNumber ?></td>
                                <td><?= $itinerary->departureFrom ?></td>
                                <td><?= $itinerary->departure ?></td>
                                <td><?= $itinerary->arrivalTo ?></td>
                                <td><?= $itinerary->arrival ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
