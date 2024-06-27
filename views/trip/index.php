<?php

use app\models\Trip;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TripSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Trips';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать рейс', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Обработать расписание', ['handle-trips'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Trip $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
