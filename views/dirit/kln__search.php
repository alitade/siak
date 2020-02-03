<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\KalenderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="kalender-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kln_id') ?>

    <?= $form->field($model, 'kr_kode') ?>

    <?= $form->field($model, 'jr_id') ?>

    <?= $form->field($model, 'pr_kode') ?>

    <?= $form->field($model, 'kln_krs') ?>

    <?php // echo $form->field($model, 'kln_masuk') ?>

    <?php // echo $form->field($model, 'kln_uts') ?>

    <?php // echo $form->field($model, 'kln_uas') ?>

    <?php // echo $form->field($model, 'kln_krs_lama') ?>

    <?php // echo $form->field($model, 'kln_uts_lama') ?>

    <?php // echo $form->field($model, 'kln_uas_lama') ?>

    <?php // echo $form->field($model, 'kln_stat') ?>

    <?php // echo $form->field($model, 'kln_sesi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
