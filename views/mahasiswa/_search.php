<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\MahasiswaSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mahasiswa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mhs_nim') ?>

    <?= $form->field($model, 'mhs_pass') ?>

    <?= $form->field($model, 'mhs_pass_kode') ?>

    <?= $form->field($model, 'mhs_angkatan') ?>

    <?= $form->field($model, 'jr_id') ?>

    <?php // echo $form->field($model, 'pr_kode') ?>

    <?php // echo $form->field($model, 'mhs_stat') ?>

    <?php // echo $form->field($model, 'ds_wali') ?>

    <?php // echo $form->field($model, 'mhs_tipe') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
