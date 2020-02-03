<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\DosenAbsen $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-absen-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']],

            'ds_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds ID...']],

            'sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sesi...']],

            'masuk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Masuk...']],

            'keluar'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Keluar...']],

            'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

            'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']],

            'tgl_absen'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
