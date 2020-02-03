<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Wali $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="wali-form">

    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'Id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']], 

'DsId'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds ID...']], 

'JrId'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr ID...']], 

'KrKd'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kd...']], 

'Status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']], 

'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
