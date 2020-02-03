<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogTransaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'ip4')->textInput() ?>

    <?= $form->field($model, 'ip6')->textInput() ?>

    <?= $form->field($model, 'user_agent')->textInput() ?>

    <?= $form->field($model, 'tgl')->textInput() ?>

    <?= $form->field($model, 'ket')->textInput() ?>

    <?= $form->field($model, 'kode')->textInput() ?>

    <?= $form->field($model, 'tb')->textInput() ?>

    <?= $form->field($model, 'pk')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
