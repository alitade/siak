<?php
use yii\helpers\Html;

$this->title = 'Tambah Data Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum', 'url' => ['/kurikulum/index']];
$this->params['breadcrumbs'][] = $this->title;
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
