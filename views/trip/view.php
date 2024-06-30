<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Trip $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="trip-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', ['cancel', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to cancel this trip?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Finish', ['finish', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'start_date',
            'end_date',
            [
                'attribute' => 'driver_id',
                'value' => function ($data) {
                    return $data->getDriverFIO();
                },                
            ],            
            [
                'attribute' => 'car_id',
                'value' => function ($data) {
                    return $data->getCarText();
                },                
            ],            
            'address_from',
            'address_to',
            'driver_avard',
            [
                'attribute' => 'status_id',
                'value' => function ($data) {
                    return $data->getStatusText();
                },                
            ],
        ],
    ]) ?>

</div>
