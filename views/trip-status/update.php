<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TripStatus $model */

$this->title = 'Update Trip Status: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trip Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trip-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
