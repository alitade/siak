<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\JurusanSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jurusan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'jr_id') ?>

    <?= $form->field($model, 'fk_id') ?>

    <?= $form->field($model, 'jr_kode_nim') ?>

    <?= $form->field($model, 'jr_nama') ?>

    <?= $form->field($model, 'jr_jenjang') ?>

    <?php // echo $form->field($model, 'jr_stat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
