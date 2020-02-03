<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mahasiswa-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Nim...']], 

'mhs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Stat...']], 

'mhs_tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Tipe...']], 

'mhs_pass'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Pass...']], 

'mhs_pass_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Pass Kode...']], 

'mhs_angkatan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Angkatan...']], 

'jr_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr ID...']], 

'pr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Kode...']], 

'ds_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Wali...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
