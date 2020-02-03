<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\LogFingers $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="log-fingers-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'fid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fid...']],

            'tgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'cat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cat...']],

            'ket'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
