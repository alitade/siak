<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenMhsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mabsen-mhs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_absen_ds') ?>

    <?= $form->field($model, 'mhs_nim') ?>

    <?= $form->field($model, 'mhs_fid') ?>

    <?= $form->field($model, 'mhs_masuk') ?>

    <?php // echo $form->field($model, 'mhs_keluar') ?>

    <?php // echo $form->field($model, 'mhs_stat') ?>

    <?php // echo $form->field($model, 'input_tipe') ?>

    <?php // echo $form->field($model, 'krs_id') ?>

    <?php // echo $form->field($model, 'krs_stat') ?>

    <?php // echo $form->field($model, 'jdwl_id') ?>

    <?php // echo $form->field($model, 'sesi') ?>

    <?php // echo $form->field($model, 'cuid') ?>

    <?php // echo $form->field($model, 'ctgl') ?>

    <?php // echo $form->field($model, 'uuid') ?>

    <?php // echo $form->field($model, 'utgl') ?>

    <?php // echo $form->field($model, 'duid') ?>

    <?php // echo $form->field($model, 'dtgl') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <?php // echo $form->field($model, 'kode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
