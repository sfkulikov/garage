<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TripStatus $model */

$this->title = 'Create Trip Status';
$this->params['breadcrumbs'][] = ['label' => 'Trip Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
