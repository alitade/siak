<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="produk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'produk') ?>

    <?= $form->field($model, 'cuid') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'utgl') ?>

    <?php // echo $form->field($model, 'duid') ?>

    <?php // echo $form->field($model, 'dtgl') ?>

    <?php // echo $form->field($model, 'Rstat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
