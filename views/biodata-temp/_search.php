<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BiodataTempSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="biodata-temp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'no_ktp') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'tempat_lahir') ?>

    <?= $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'jk') ?>

    <?php // echo $form->field($model, 'alamat_ktp') ?>

    <?php // echo $form->field($model, 'kota') ?>

    <?php // echo $form->field($model, 'kode_pos') ?>

    <?php // echo $form->field($model, 'propinsi') ?>

    <?php // echo $form->field($model, 'negara') ?>

    <?php // echo $form->field($model, 'agama') ?>

    <?php // echo $form->field($model, 'status_ktp') ?>

    <?php // echo $form->field($model, 'pekerjaan') ?>

    <?php // echo $form->field($model, 'kewarganegaraan') ?>

    <?php // echo $form->field($model, 'berlaku_ktp') ?>

    <?php // echo $form->field($model, 'ibu_kandung') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'alamat_tinggal') ?>

    <?php // echo $form->field($model, 'kota_tinggal') ?>

    <?php // echo $form->field($model, 'kode_pos_tinggal') ?>

    <?php // echo $form->field($model, 'tlp') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'parent') ?>

    <?php // echo $form->field($model, 'cuid') ?>

    <?php // echo $form->field($model, 'ctgl') ?>

    <?php // echo $form->field($model, 'id_') ?>

    <?php // echo $form->field($model, 'kd_agama') ?>

    <?php // echo $form->field($model, 'kd_kerja') ?>

    <?php // echo $form->field($model, 'glr_depan') ?>

    <?php // echo $form->field($model, 'glr_belakang') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
