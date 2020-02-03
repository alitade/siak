<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Vakasi $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="vakasi-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']], 

'tgs1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tgs1...']], 

'tgs2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tgs2...']], 

'tgs3'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tgs3...']], 

'quis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Quis...']], 

'uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uts...']], 

'uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uas...']], 

'tgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
