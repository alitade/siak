<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Program $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="program-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'pr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Kode...']], 

'pr_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Stat...']], 

'pr_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Nama...']], 

'pr_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Nim...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
