<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="kalender-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'kr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kode...']], 

'jr_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr ID...']], 

'pr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pr Kode...']], 

'kln_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Stat...']], 

'kln_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Sesi...']], 

'kln_krs'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'kln_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'kln_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'kln_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'kln_krs_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Krs Lama...']], 

'kln_uts_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Uts Lama...']], 

'kln_uas_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Uas Lama...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
