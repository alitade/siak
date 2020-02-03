<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Nilai $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="nilai-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'npm'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Npm...']], 

'kode_mk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Mk...']], 

'nama_mk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama Mk...']], 

'huruf'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Huruf...']], 

'tahun'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tahun...']], 

'stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Stat...']], 

'kat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kat...']], 

'semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Semester...']], 

'sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sks...']], 

'nilai'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nilai...']], 

'tgl_input'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
