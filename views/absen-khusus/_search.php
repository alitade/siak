<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\AbsenKhususSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="absen-khusus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mhs_nim') ?>

    <?= $form->field($model, 'kr_kode') ?>

    <?= $form->field($model, 'tgl_exp') ?>

    <?= $form->field($model, 'tgl_ins') ?>

    <?php // echo $form->field($model, 'tipe') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
