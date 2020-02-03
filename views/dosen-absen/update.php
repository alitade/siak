<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenAbsen $model
 */

$this->title = 'Update Dosen Absen: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Absens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-absen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
