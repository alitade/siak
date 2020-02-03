<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenDosenSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mabsen-dosen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ds_id') ?>

    <?= $form->field($model, 'ds_id1') ?>

    <?= $form->field($model, 'ds_get_id') ?>

    <?= $form->field($model, 'ds_get_fid') ?>

    <?php // echo $form->field($model, 'ds_masuk') ?>

    <?php // echo $form->field($model, 'ds_keluar') ?>

    <?php // echo $form->field($model, 'ds_stat') ?>

    <?php // echo $form->field($model, 'input_tipe') ?>

    <?php // echo $form->field($model, 'jdwl_id') ?>

    <?php // echo $form->field($model, 'jdwl_kls') ?>

    <?php // echo $form->field($model, 'jdwl_hari') ?>

    <?php // echo $form->field($model, 'jdwl_masuk') ?>

    <?php // echo $form->field($model, 'jdwl_keluar') ?>

    <?php // echo $form->field($model, 'mtk_kode') ?>

    <?php // echo $form->field($model, 'mtk_nama') ?>

    <?php // echo $form->field($model, 'sesi') ?>

    <?php // echo $form->field($model, 'tgl_normal') ?>

    <?php // echo $form->field($model, 'tgl_perkuliahan') ?>

    <?php // echo $form->field($model, 'tipe') ?>

    <?php // echo $form->field($model, 'cuid') ?>

    <?php // echo $form->field($model, 'ctgl') ?>

    <?php // echo $form->field($model, 'uuid') ?>

    <?php // echo $form->field($model, 'utgl') ?>

    <?php // echo $form->field($model, 'duid') ?>

    <?php // echo $form->field($model, 'dtgl') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <?php // echo $form->field($model, 'kr_kode_') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
