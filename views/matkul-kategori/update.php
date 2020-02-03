<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulKategori $model
 */

$this->title = 'Update Matkul Kategori: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Matkul Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="matkul-kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
