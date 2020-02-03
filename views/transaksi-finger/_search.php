<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFingerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaksi-finger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'krs_id') ?>

    <?= $form->field($model, 'krs_stat') ?>

    <?= $form->field($model, 'ds_fid') ?>

    <?= $form->field($model, 'ds_fid1') ?>

    <?php // echo $form->field($model, 'mtk_kode') ?>

    <?php // echo $form->field($model, 'mtk_nama') ?>

    <?php // echo $form->field($model, 'jdwl_id') ?>

    <?php // echo $form->field($model, 'jdwl_hari') ?>

    <?php // echo $form->field($model, 'jdwl_masuk') ?>

    <?php // echo $form->field($model, 'jdwl_keluar') ?>

    <?php // echo $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'mhs_fid') ?>

    <?php // echo $form->field($model, 'mhs_masuk') ?>

    <?php // echo $form->field($model, 'mhs_keluar') ?>

    <?php // echo $form->field($model, 'mhs_stat') ?>

    <?php // echo $form->field($model, 'ds_masuk') ?>

    <?php // echo $form->field($model, 'ds_keluar') ?>

    <?php // echo $form->field($model, 'ds_stat') ?>

    <?php // echo $form->field($model, 'ds_get_fid') ?>

    <?php // echo $form->field($model, 'tgl_ins') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
