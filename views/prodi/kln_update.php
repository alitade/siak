<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Ubah Kalender Akademik: ' . ' ' . $model->kr->kr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['kln']];
$this->params['breadcrumbs'][] = ['label' => $model->kr->kr_nama, 'url' => ['view', 'id' => $model->kln_id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="kalender-update">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('kln__form', [
        'model' => $model,
    ]) ?>

</div>
