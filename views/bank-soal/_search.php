<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BankSoalSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bank-soal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'mtk_kode') ?>

    <?= $form->field($model, 'jenis') ?>

    <?= $form->field($model, 'jml_soal') ?>

    <?= $form->field($model, 'tgl_upload') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
