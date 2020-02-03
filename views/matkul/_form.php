<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="matkul-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'mtk_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kode...']], 

'mtk_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Nama...']], 

'mtk_kat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kat...']], 

'mtk_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Stat...']], 

'jr_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr ID...']], 

'penanggungjawab'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Penanggungjawab...']], 

'mtk_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Sesi...']], 

'mtk_sub'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Sub...']], 

'mtk_semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Semester...']], 

'mtk_jenis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Jenis...']], 

'mtk_sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Sks...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
