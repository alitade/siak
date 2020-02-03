<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AbsenAwal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="absen-awal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'GKode')->textInput() ?>

    <?= $form->field($model, 'jdwl_masuk')->textInput() ?>

    <?= $form->field($model, 'jdwl_keluar')->textInput() ?>

    <?= $form->field($model, 'tgl')->textInput() ?>

    <?= $form->field($model, 'ds_masuk')->textInput() ?>

    <?= $form->field($model, 'ds_keluar')->textInput() ?>

    <?= $form->field($model, 'tipe')->textInput() ?>

    <?= $form->field($model, 'ds_fid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
