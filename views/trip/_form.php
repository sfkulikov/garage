<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TripStatus;
use app\models\Driver;
use app\models\Car;

/** @var yii\web\View $this */
/** @var app\models\Trip $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="trip-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'driver_id')->dropdownList(
        Driver::find()->select(['fio',
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите водителя']
    ) ?>

    <?= $form->field($model, 'car_id')->dropdownList(
        Car::find()->select(["CONCAT(model, ' (', car_number,')')",
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите автомобиль']
    ) ?>

    <?= $form->field($model, 'address_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_avard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->dropdownList(
        TripStatus::find()->select(['status_name',
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите статус']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
