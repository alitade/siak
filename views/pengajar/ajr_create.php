<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 */

$this->title = 'Tambah Pengajar';
$this->params['breadcrumbs'][] = ['label' => 'Pengajar', 'url' => ['/pengajar/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
	<div class="panel-heading"><h4 class="panel-title"><?= $this->title ?></h4></div>
    <div class="panel-body">
    <?= 
	$this->render('ajr__form',[
        'model' => $model,
    ]) 
	?>
    </div>
</div>

