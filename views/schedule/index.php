<?php

use app\models\Schedule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var app\models\ScheduleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Расписание';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новое расписание', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'driver_id',
            [
                'attribute' => 'driver_id',
                'value' => function ($data) {
                    return $data->getDriverFIO(); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            //'car_id',
            [
                'attribute' => 'car_id',
                'value' => function ($data) {
                    return $data->getCarText(); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],            
            'start_time',
            'end_time',
            'address_from',
            'address_to',
            'driver_avard',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Schedule $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
