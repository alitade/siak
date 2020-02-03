<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\PesertaUjianSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="peserta-ujian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdUjian') ?>

    <?= $form->field($model, 'Krs_id') ?>

    <?= $form->field($model, 'jdwl_id_') ?>

    <?= $form->field($model, 'RStat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
