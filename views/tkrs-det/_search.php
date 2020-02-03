<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsDetSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tkrs-det-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'mtk_kode') ?>

    <?= $form->field($model, 'mtk_sks') ?>

    <?= $form->field($model, 'mtk_nama') ?>

    <?php // echo $form->field($model, 'mhs_nim') ?>

    <?php // echo $form->field($model, 'kr_kode') ?>

    <?php // echo $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'tgl_jdwl') ?>

    <?php // echo $form->field($model, 'krs_stat') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <?php // echo $form->field($model, 'krs_ulang') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <?php // echo $form->field($model, 'cuid') ?>

    <?php // echo $form->field($model, 'ctgl') ?>

    <?php // echo $form->field($model, 'uuid') ?>

    <?php // echo $form->field($model, 'utgl') ?>

    <?php // echo $form->field($model, 'duid') ?>

    <?php // echo $form->field($model, 'dtgl') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
