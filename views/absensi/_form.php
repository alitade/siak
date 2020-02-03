<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Absensi $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="absensi-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'krs_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs ID...']], 

'jdwl_id_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']], 

'jdwal_tgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 

'jdwl_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Sesi...']], 

'jdwl_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Stat...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
