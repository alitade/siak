<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Tambah Kalender Akademik';
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['/kalender/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p style="clear:both"></p>
<div class="panel panel-primary">
	<div class="panel-heading"><h4 class="panel-title"><?= $this->title?></h4></div>
    <div class="panel-body">
    <?= $this->render('kln__form', [
        'model' => $model,
		'subAkses'=>$subAkses,
    ]) ?>
    </div>
</div>
