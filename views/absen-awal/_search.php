<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AbsenAwalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="absen-awal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'GKode') ?>

    <?= $form->field($model, 'jdwl_masuk') ?>

    <?= $form->field($model, 'jdwl_keluar') ?>

    <?= $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'ds_masuk') ?>

    <?php // echo $form->field($model, 'ds_keluar') ?>

    <?php // echo $form->field($model, 'tipe') ?>

    <?php // echo $form->field($model, 'ds_fid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
