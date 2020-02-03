<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulKr $model
 */

$this->title = 'Update Kurikulum Matakuliah :' . ' ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum Matakuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="matkul-kr-update">
    <?= $this->render('_form',[
        'model' => $model,
        'EDT'=>true,
    ]) ?>

</div>
