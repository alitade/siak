<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Mata Kuliahearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="matkul-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mtk_kode') ?>

    <?= $form->field($model, 'mtk_nama') ?>

    <?= $form->field($model, 'mtk_sks') ?>

    <?= $form->field($model, 'mtk_kat') ?>

    <?= $form->field($model, 'mtk_stat') ?>

    <?php // echo $form->field($model, 'jr_id') ?>

    <?php // echo $form->field($model, 'penanggungjawab') ?>

    <?php // echo $form->field($model, 'mtk_sesi') ?>

    <?php // echo $form->field($model, 'mtk_sub') ?>

    <?php // echo $form->field($model, 'mtk_semester') ?>

    <?php // echo $form->field($model, 'mtk_jenis') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
