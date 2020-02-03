<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulKategori $model
 */

$this->title = 'Create Matkul Kategori';
$this->params['breadcrumbs'][] = ['label' => 'Matkul Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-kategori-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
