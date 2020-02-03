<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\BobotNilaiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bobot-nilai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kln_id') ?>

    <?= $form->field($model, 'mtk_kode') ?>

    <?= $form->field($model, 'ds_nidn') ?>

    <?= $form->field($model, 'nb_tgs1') ?>

    <?php // echo $form->field($model, 'nb_tgs2') ?>

    <?php // echo $form->field($model, 'nb_tgs3') ?>

    <?php // echo $form->field($model, 'nb_tambahan') ?>

    <?php // echo $form->field($model, 'nb_quis') ?>

    <?php // echo $form->field($model, 'nb_uts') ?>

    <?php // echo $form->field($model, 'nb_uas') ?>

    <?php // echo $form->field($model, 'B') ?>

    <?php // echo $form->field($model, 'C') ?>

    <?php // echo $form->field($model, 'D') ?>

    <?php // echo $form->field($model, 'E') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
