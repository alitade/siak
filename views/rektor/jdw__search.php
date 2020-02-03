<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\JadwalSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jadwal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'bn_id') ?>

    <?= $form->field($model, 'rg_kode') ?>

    <?= $form->field($model, 'semester') ?>

    <?= $form->field($model, 'jdwl_hari') ?>

    <?php // echo $form->field($model, 'jdwl_masuk') ?>

    <?php // echo $form->field($model, 'jdwl_keluar') ?>

    <?php // echo $form->field($model, 'jdwl_kls') ?>

    <?php // echo $form->field($model, 'jdwl_uts') ?>

    <?php // echo $form->field($model, 'jdwl_uas') ?>

    <?php // echo $form->field($model, 'jdwl_uts_out') ?>

    <?php // echo $form->field($model, 'jdwl_uas_out') ?>

    <?php // echo $form->field($model, 'rg_uts') ?>

    <?php // echo $form->field($model, 'rg_uas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
