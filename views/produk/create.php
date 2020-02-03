<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Produk $model
 */

$this->title = 'Tambah Data Produk';
$this->params['breadcrumbs'][] = ['label' => 'Produk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>
