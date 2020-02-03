<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsDet $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tkrs-det-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],

            'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']],

            'mtk_sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Sks...']],

            'cuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cuid...']],

            'uuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uuid...']],

            'duid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Duid...']],

            'mtk_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kode...']],

            'mtk_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Nama...']],

            'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Nim...']],

            'kr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kode...']],

            'krs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Stat...']],

            'ket'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket...']],

            'krs_ulang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Ulang...']],

            'RStat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

            'tgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'tgl_jdwl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'ctgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'utgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'dtgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
