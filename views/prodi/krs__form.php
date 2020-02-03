<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="krs-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'krs_tgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'jdwl_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl ID...']], 

'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Nim...']], 

'krs_tgs1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Tgs1...']], 

'krs_tgs2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Tgs2...']], 

'krs_tgs3'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Tgs3...']], 

'krs_tambahan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Tambahan...']], 

'krs_quis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Quis...']], 

'krs_uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Uts...']], 

'krs_uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Uas...']], 

'krs_grade'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Grade...']], 

'krs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Stat...']], 

'krs_ulang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Ulang...']], 

'kr_kode_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kode...']], 

'ds_nidn_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Nidn...']], 

'ds_nm_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Nm...']], 

'mtk_kode_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kode...']], 

'mtk_nama_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Nama...']], 

'sks_'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sks...']], 

'krs_tot'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Krs Tot...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
