<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Ruangearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ruang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'rg_kode') ?>

    <?= $form->field($model, 'rg_nama') ?>

    <?= $form->field($model, 'kapasitas') ?>

    <?= $form->field($model, 'IdGedung') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
