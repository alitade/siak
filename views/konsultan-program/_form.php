<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\KonsultanProgram $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="konsultan-program-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'konsultan_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Konsultan ID...']],

            'program_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Program ID...']],

            'jurusan_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jurusan ID...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
