<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\KrsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="krs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'krs_id') ?>

    <?= $form->field($model, 'krs_tgl') ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'mhs_nim') ?>

    <?= $form->field($model, 'krs_tgs1') ?>

    <?php // echo $form->field($model, 'krs_tgs2') ?>

    <?php // echo $form->field($model, 'krs_tgs3') ?>

    <?php // echo $form->field($model, 'krs_tambahan') ?>

    <?php // echo $form->field($model, 'krs_quis') ?>

    <?php // echo $form->field($model, 'krs_uts') ?>

    <?php // echo $form->field($model, 'krs_uas') ?>

    <?php // echo $form->field($model, 'krs_tot') ?>

    <?php // echo $form->field($model, 'krs_grade') ?>

    <?php // echo $form->field($model, 'krs_stat') ?>

    <?php // echo $form->field($model, 'krs_ulang') ?>

    <?php // echo $form->field($model, 'kr_kode_') ?>

    <?php // echo $form->field($model, 'ds_nidn_') ?>

    <?php // echo $form->field($model, 'ds_nm_') ?>

    <?php // echo $form->field($model, 'mtk_kode_') ?>

    <?php // echo $form->field($model, 'mtk_nama_') ?>

    <?php // echo $form->field($model, 'sks_') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
