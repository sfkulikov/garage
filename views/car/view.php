<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Car $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Автомашины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="car-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить автомобиль??',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'model',
            'car_number',
            [
                'attribute' => 'car_type_id',
                'value' => function ($data) {
                    return $data->getCarTypeText(); // $data['name'] for array data, e.g. using SqlDataProvider.
                },
                /*

                echo $form2->field($model2, 'parrent')->dropDownList($categoryes,$params)->label('Родительский раздел');
	?>
 
                'filter' => ['0' => 'Легковая', '1' => 'Грузовая', '2' => 'Автобус'],
                'filterInputOptions' => ['prompt' => 'Все', 'class' => 'form-control', 'car_type_id' => null] */               
            ],
        ],
    ]) ?>

</div>
