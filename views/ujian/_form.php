<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ujian */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ujian-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'IdJadwal')->textInput() ?>
    <?= $form->field($model, 'Kat')->textInput() ?>
    <?= $form->field($model, 'Tgl')->textInput() ?>
    <?= $form->field($model, 'Masuk')->textInput() ?>
    <?= $form->field($model, 'Keluar')->textInput() ?>
    <?= $form->field($model, 'RgKode')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
