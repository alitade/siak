<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Update Pengajar: ' . ' ' . $model->ds->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pengajar', 'url' => ['ajr']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['ajr-view', 'id' => $model->ds->ds_nm]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-update">

   <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('ajr__formU', [
        'model' => $model,
        'kalender' => $kalender,
    ]) ?>

</div>
