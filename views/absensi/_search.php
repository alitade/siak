<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\AbsensiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="absensi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'krs_id') ?>

    <?= $form->field($model, 'jdwl_id_') ?>

    <?= $form->field($model, 'jdwl_stat') ?>

    <?= $form->field($model, 'jdwal_tgl') ?>

    <?php // echo $form->field($model, 'jdwl_sesi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
