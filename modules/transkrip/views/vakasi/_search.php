<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\VakasiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="vakasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jdwl_id') ?>

    <?= $form->field($model, 'tgs1') ?>

    <?= $form->field($model, 'tgs2') ?>

    <?= $form->field($model, 'tgs3') ?>

    <?php // echo $form->field($model, 'quis') ?>

    <?php // echo $form->field($model, 'uts') ?>

    <?php // echo $form->field($model, 'uas') ?>

    <?php // echo $form->field($model, 'tgl') ?>

    <?php // echo $form->field($model, 'RStat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
