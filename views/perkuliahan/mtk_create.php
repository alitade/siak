<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 */

$this->title = 'Tambah Matakuliah';
$this->params['breadcrumbs'][] = ['label' => 'Matakuliah', 'url' => ['mtk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-create">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('mtk_form', [
        'model' => $model,
    ]) ?>

</div>
