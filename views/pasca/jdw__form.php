<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jadwal-form">
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Jadwal</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

		'bn_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Bn ID...']], 
		
		'semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Semester...']], 
		
		'jdwl_hari'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Hari...']], 
		
		'jdwl_masuk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Masuk...']], 
		
		'jdwl_keluar'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Keluar...']], 
		
		'rg_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Kode...']], 
		
		'jdwl_kls'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jdwl Kls...']], 
		
		'rg_uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Uts...']], 
		
		'rg_uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Uas...']], 
		
		'jdwl_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
		
		'jdwl_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
		
		'jdwl_uts_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
		
		'jdwl_uas_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

    ]


    ]);
?>
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>

<?php    ActiveForm::end(); ?>
    </div>
    </div>
</div>
