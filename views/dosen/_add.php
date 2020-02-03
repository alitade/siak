<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'ds_nidn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Nidn...']], 

'ds_user'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds User...']], 

'ds_pass'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Pass...']], 

'ds_pass_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Pass Kode...']], 

'ds_nm'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama Dosen...']], 

'ds_kat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Kat...']], 

'ds_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Email...']], 

'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']], 

'ds_tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Tipe...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
