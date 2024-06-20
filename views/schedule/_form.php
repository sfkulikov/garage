<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Car; 
use app\models\Driver;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var app\models\Schedule $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'car_id')->dropdownList(
        Car::find()->select(['model',
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите автомобиль']
    ) ?>

    <?= $form->field($model, 'driver_id')->dropdownList(
        Driver::find()->select(['fio',
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите водителя']
    ) ?>

    <?= $form->field($model, 'start_time')->widget(DateControl::classname(),
        [
            'type' => DateControl::FORMAT_TIME,
            'options' => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'showSeconds' => false,
                ]
            ]            
       ])
    ?>

    <?= $form->field($model, 'end_time')->widget(DateControl::classname(),
        [
            'type' => DateControl::FORMAT_TIME,
            'options' => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'showSeconds' => false,
                ]
            ]            
       ])
    ?>

    <?= $form->field($model, 'address_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'driver_avard')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
