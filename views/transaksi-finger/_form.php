<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaksi-finger-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'krs_id')->textInput() ?>

    <?= $form->field($model, 'krs_stat')->textInput() ?>

    <?= $form->field($model, 'ds_fid')->textInput() ?>

    <?= $form->field($model, 'ds_fid1')->textInput() ?>

    <?= $form->field($model, 'mtk_kode')->textInput() ?>

    <?= $form->field($model, 'mtk_nama')->textInput() ?>

    <?= $form->field($model, 'jdwl_id')->textInput() ?>

    <?= $form->field($model, 'jdwl_hari')->textInput() ?>

    <?= $form->field($model, 'jdwl_masuk')->textInput() ?>

    <?= $form->field($model, 'jdwl_keluar')->textInput() ?>

    <?= $form->field($model, 'tgl')->textInput() ?>

    <?= $form->field($model, 'mhs_fid')->textInput() ?>

    <?= $form->field($model, 'mhs_masuk')->textInput() ?>

    <?= $form->field($model, 'mhs_keluar')->textInput() ?>

    <?= $form->field($model, 'mhs_stat')->textInput() ?>

    <?= $form->field($model, 'ds_masuk')->textInput() ?>

    <?= $form->field($model, 'ds_keluar')->textInput() ?>

    <?= $form->field($model, 'ds_stat')->textInput() ?>

    <?= $form->field($model, 'ds_get_fid')->textInput() ?>

    <?= $form->field($model, 'tgl_ins')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
