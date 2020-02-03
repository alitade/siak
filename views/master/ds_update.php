<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Ubah Dosen: ' . ' ' . $model->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Dosen', 'url' => ['ds']];
$this->params['breadcrumbs'][] = ['label' => $model->ds_nm, 'url' => ['view', 'id' => $model->ds_id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="dosen-update">
    <?= $this->render('ds__form', ['model' => $model,]) ?>
</div>
