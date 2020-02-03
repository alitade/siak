<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsDet $model
 */

$this->title = 'Update Tkrs Det: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tkrs Dets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tkrs-det-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
