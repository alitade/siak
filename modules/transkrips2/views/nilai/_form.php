<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\transkrip\models\Nilai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nilai-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'npm')->textInput() ?>
    <?= $form->field($model, 'kode_mk')->textInput() ?>

    <?= $form->field($model, 'nama_mk')->textInput() ?>

    <?= $form->field($model, 'semester')->textInput() ?>

    <?= $form->field($model, 'sks')->textInput() ?>

    <?= $form->field($model, 'huruf')->textInput() ?>

    <?= $form->field($model, 'nilai')->textInput() ?>

    <?= $form->field($model, 'tahun')->textInput() ?>

    <?= $form->field($model, 'tgl_input')->textInput() ?>

    <?= $form->field($model, 'stat')->textInput() ?>

    <?= $form->field($model, 'kat')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
