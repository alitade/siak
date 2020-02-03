<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\JadwalTmpSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jadwal-tmp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'ds_nidn') ?>

    <?= $form->field($model, 'rg_kode') ?>

    <?= $form->field($model, 'jdwl_hari') ?>

    <?php // echo $form->field($model, 'jdwl_keluar') ?>

    <?php // echo $form->field($model, 'jdwl_masuk') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
