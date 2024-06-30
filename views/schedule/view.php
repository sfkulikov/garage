<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Schedule $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Расписание', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="schedule-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить расписание?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            //'car_id',
            [
                'attribute' => 'car_id',
                'value' => function ($data) {
                    return $data->getCarText(); // $data['name'] for array data, e.g. using SqlDataProvider.
                },                
            ],
            //'driver_id',
            [
                'attribute' => 'driver_id',
                'value' => function ($data) {
                    return $data->getDriverFIO(); // $data['name'] for array data, e.g. using SqlDataProvider.
                },                
            ],
            'start_time',
            'end_time',
            'address_from',
            'address_to',
            'driver_avard',
        ],
    ]) ?>

</div>
