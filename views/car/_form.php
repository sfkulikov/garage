<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CarType;

/** @var yii\web\View $this */
/** @var app\models\Car $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="car-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'car_type_id')->dropdownList(
        CarType::find()->select(['type_name',
        'id'])->indexBy('id')->column(),
        ['prompt'=>'Выберите тип автомобиля']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
