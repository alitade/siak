<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 */

$this->title = 'Ubah Matkul: ' . ' ' . $model->mtk_nama;
$this->params['breadcrumbs'][] = ['label' => 'Matakuliah', 'url' => ['mtk']];
$this->params['breadcrumbs'][] = ['label' => $model->mtk_nama, 'url' => ['view', 'id' => $model->mtk_kode]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="matkul-update">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('mtk_form', [
        'model' => $model,
    ]) ?>

</div>
