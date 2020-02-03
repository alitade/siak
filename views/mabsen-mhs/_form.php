<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenMhs $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mabsen-mhs-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],

            'id_absen_ds'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Id Absen Ds...']],

            'mhs_fid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Fid...']],

            'krs_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs ID...']],

            'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']],

            'cuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cuid...']],

            'uuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uuid...']],

            'duid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Duid...']],

            'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Nim...']],

            'mhs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Stat...']],

            'input_tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Input Tipe...']],

            'krs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Stat...']],

            'sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sesi...']],

            'ket'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket...']],

            'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

            'kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode...']],

            'mhs_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'mhs_keluar'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'ctgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'utgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'dtgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
