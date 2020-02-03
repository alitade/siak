<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Ubah Kalender Akademik: ' . ' ' . @$model->kr->kr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['/kalender/index']];
$this->params['breadcrumbs'][] = ['label' => @$model->kr->kr_nama, 'url' => ['view', 'id' => $model->kln_id]];
$this->params['breadcrumbs'][] = "Update";
?>
<p style="clear:both"></p>
<div class="panel panel-primary">
	<div class="panel-heading"><h4 class="panel-title"><?= $this->title?></h4></div>
    <div class="panel-body">
    <?= $this->render('kln__formOne', [
        'model' => $model,
    ]) ?>
    </div>
</div>