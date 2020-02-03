<?php
use yii\helpers\Html;
$this->title = 'Tambah Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Dosen','url'=>['ds']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-create">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= 
	$this->render('ds__form', [
        'model' => $model,
    ])
	?>
</div>