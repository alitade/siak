<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fakultas */

$this->title = 'Ubah Fakultas: ' . ' ' . $model->fk_nama;
$this->params['breadcrumbs'][] = ['label' => 'Fakultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fk_nama, 'url' => ['view', 'id' => $model->fk_id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="fakultas-update">
    <?= $this->render('fk__form', [
        'model' => $model,
		'title'=>$this->title,
    ]) ?>

</div>
