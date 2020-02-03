<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\DosenAbsenSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-absen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'ds_id') ?>

    <?= $form->field($model, 'sesi') ?>

    <?= $form->field($model, 'tgl_absen') ?>

    <?php // echo $form->field($model, 'masuk') ?>

    <?php // echo $form->field($model, 'keluar') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
