<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\DosenSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ds_id') ?>

    <?= $form->field($model, 'ds_nidn') ?>

    <?= $form->field($model, 'ds_user') ?>

    <?= $form->field($model, 'ds_pass') ?>

    <?= $form->field($model, 'ds_pass_kode') ?>

    <?php // echo $form->field($model, 'ds_nm') ?>

    <?php // echo $form->field($model, 'ds_tipe') ?>

    <?php // echo $form->field($model, 'ds_kat') ?>

    <?php // echo $form->field($model, 'ds_email') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
