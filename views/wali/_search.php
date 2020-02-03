<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\WaliSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="wali-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'JrId') ?>

    <?= $form->field($model, 'DsId') ?>

    <?= $form->field($model, 'KrKd') ?>

    <?= $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
