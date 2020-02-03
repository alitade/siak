<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kurikulum $model
 */

$this->title = 'Update Kurikulum: ' . ' ' . $model->kr_kode;
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum', 'url' => ['/kurikulum/index']];
$this->params['breadcrumbs'][] = ['label' => $model->kr_kode, 'url' => ['/kurikulum/view', 'id' => $model->kr_kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<p></p>
<fieldset class="panel panel-primary">
	<div class="panel-heading" style="padding:2px"><p class="panel-title"><?= $this->title ?></p></div>
	<div class="panel-body">     
    <?= $this->render('/kurikulum/kr__form', [
        'model' => $model,
    ]) 
	?>
    </div>
</fieldset>
