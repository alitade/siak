<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 */

$this->title = 'Update Matkul: ' . ' ' . $model->mtk_kode;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mtk_kode, 'url' => ['view', 'id' => $model->mtk_kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="matkul-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
