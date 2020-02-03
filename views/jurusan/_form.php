<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jurusan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'jr_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr ID...']], 

'jr_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr Stat...']], 

'fk_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fk ID...']], 

'jr_kode_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr Kode Nim...']], 

'jr_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr Nama...']], 

'jr_jenjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr Jenjang...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
