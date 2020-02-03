<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHargaSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="produk-harga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kode_produk') ?>

    <?= $form->field($model, 'harga') ?>

    <?= $form->field($model, 'aktif') ?>

    <?= $form->field($model, 'cuid') ?>

    <?= $form->field($model, 'uuid') ?>

    <?php // echo $form->field($model, 'utgl') ?>

    <?php // echo $form->field($model, 'duid') ?>

    <?php // echo $form->field($model, 'dtgl') ?>

    <?php // echo $form->field($model, 'Rstat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
