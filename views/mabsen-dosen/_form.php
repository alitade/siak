<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenDosen $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mabsen-dosen-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],

            'ds_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds ID...']],

            'ds_id1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Id1...']],

            'ds_get_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Get ID...']],

            'ds_get_fid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Get Fid...']],

            'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']],

            'cuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cuid...']],

            'uuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uuid...']],

            'duid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Duid...']],

            'ds_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'ds_keluar'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'jdwl_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'jdwl_keluar'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_TIME]],

            'tgl_normal'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'tgl_perkuliahan'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'ctgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'utgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'dtgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'ds_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Stat...']],

            'input_tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Input Tipe...']],

            'jdwl_kls'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Kls...']],

            'jdwl_hari'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Hari...']],

            'mtk_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kode...']],

            'mtk_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Nama...']],

            'sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sesi...']],

            'tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tipe...']],

            'ket'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket...']],

            'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
