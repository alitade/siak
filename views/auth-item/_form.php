<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\AuthItem $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...']],

            'type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Type...']],

            'description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Description...','rows'=> 6]],

            'rule_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rule Name...']],

            'data'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Data...','rows'=> 6]],

            'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']],

            'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
