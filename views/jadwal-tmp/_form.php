<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\JadwalTmp $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jadwal-tmp-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']], 

'ds_nidn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Nidn...']], 

'rg_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Kode...']], 

'jdwl_hari'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Hari...']], 

'jdwl_keluar'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Keluar...']], 

'jdwl_masuk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Masuk...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
