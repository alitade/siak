<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\ProgramDaftarSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="program-daftar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'program_id') ?>

    <?= $form->field($model, 'nama_program') ?>

    <?= $form->field($model, 'identitas_id') ?>

    <?= $form->field($model, 'aktif') ?>

    <?php // echo $form->field($model, 'kode_nim') ?>

    <?php // echo $form->field($model, 'group') ?>

    <?php // echo $form->field($model, 'party') ?>

    <?php // echo $form->field($model, 'kode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
