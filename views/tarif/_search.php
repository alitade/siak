<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\TarifSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tarif-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'program') ?>

    <?= $form->field($model, 'jenjang') ?>

    <?= $form->field($model, 'check') ?>

    <?= $form->field($model, 'status_beban') ?>

    <?php // echo $form->field($model, 'maksimum') ?>

    <?php // echo $form->field($model, 'utama') ?>

    <?php // echo $form->field($model, 'kelas') ?>

    <?php // echo $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'jurusan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
