<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\AnggeSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="angge-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Fk') ?>

    <?= $form->field($model, 'Username') ?>

    <?= $form->field($model, 'Pass') ?>

    <?= $form->field($model, 'PassKode') ?>

    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
