<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHarga $model
 */

$this->title = 'Update Produk Harga: ' . ' ' . $model->harga;
$this->params['breadcrumbs'][] = ['label' => 'Produk Hargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->harga, 'url' => ['view', 'harga' => $model->harga, 'kode_produk' => $model->kode_produk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="produk-harga-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
