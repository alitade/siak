<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Angge $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="angge-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'Fk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fk...']], 

'Username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Username...']], 

'Pass'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Enter Pass...']], 

'PassKode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pass Kode...']], 

'Tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tipe...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
