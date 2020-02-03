<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\WisudaSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="wisuda-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jr_id') ?>

    <?= $form->field($model, 'npm') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'mtk_kode') ?>

    <?php // echo $form->field($model, 'pembimbing') ?>

    <?php // echo $form->field($model, 'ds_id_') ?>

    <?php // echo $form->field($model, 'skripsi_indo') ?>

    <?php // echo $form->field($model, 'skripsi_end') ?>

    <?php // echo $form->field($model, 'no_urut') ?>

    <?php // echo $form->field($model, 'kode') ?>

    <?php // echo $form->field($model, 'tgl_lulus') ?>

    <?php // echo $form->field($model, 'predikat') ?>

    <?php // echo $form->field($model, 'nilai') ?>

    <?php // echo $form->field($model, 'pejabat1') ?>

    <?php // echo $form->field($model, 'pejabat2') ?>

    <?php // echo $form->field($model, 'tgl_cetak') ?>

    <?php // echo $form->field($model, 'tgl') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
