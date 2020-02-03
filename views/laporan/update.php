<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LAbsenDosen $model
 */

$this->title = 'Update Labsen Dosen: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Labsen Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="labsen-dosen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
