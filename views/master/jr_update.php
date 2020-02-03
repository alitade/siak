<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 */

$this->title = 'Ubah Jurusan: ' . ' ' . $model->jr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Jurusan', 'url' => ['jr']];
$this->params['breadcrumbs'][] = ['label' => $model->jr_nama, 'url' => ['view', 'id' => $model->jr_id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="jurusan-update">
    <?= ($model?$this->render('jr__form', [
        'model' => $model,
		'title'=>$this->title,
    ]):"Data Tidak ada") ?>
</div>
