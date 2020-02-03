<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\ProgramDaftar $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="program-daftar-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'program_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Program ID...']],

            'nama_program'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama Program...']],

            'identitas_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Identitas ID...']],

            'aktif'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Aktif...']],

            'kode_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Nim...']],

            'group'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Group...']],

            'party'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Party...']],

            'kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
