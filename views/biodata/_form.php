<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Biodata $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="biodata-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],

            'kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode...']],

            'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter No Ktp...']],

            'nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama...']],

            'tempat_lahir'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tempat Lahir...']],

            'jk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jk...']],

            'alamat_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Alamat Ktp...']],

            'kota'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kota...']],

            'kode_pos'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Pos...']],

            'propinsi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Propinsi...']],

            'negara'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Negara...']],

            'agama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Agama...']],

            'status_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Ktp...']],

            'kewarganegaraan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kewarganegaraan...']],

            'ibu_kandung'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ibu Kandung...']],

            'photo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Photo...']],

            'parent'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Parent...']],

            'Rstat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

            'tanggal_lahir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'berlaku_ktp'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'ctgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'utgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'dtgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'pekerjaan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pekerjaan...']],

            'cuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cuid...']],

            'uuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uuid...']],

            'duid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Duid...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
