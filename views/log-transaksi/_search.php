<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogTransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="log-transaksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'ip4') ?>

    <?= $form->field($model, 'ip6') ?>

    <?= $form->field($model, 'user_agent') ?>

    <?php // echo $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <?php // echo $form->field($model, 'kode') ?>

    <?php // echo $form->field($model, 'tb') ?>

    <?php // echo $form->field($model, 'pk') ?>

    <?php // echo $form->field($model, 'aktifitas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
