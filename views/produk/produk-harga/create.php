<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHarga $model
 */

$this->title = 'Create Produk Harga';
$this->params['breadcrumbs'][] = ['label' => 'Produk Hargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-harga-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
