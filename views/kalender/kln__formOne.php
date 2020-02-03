<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

$Dkrs = date_create($model->kln_krs);
$Dkrs = date_add($Dkrs, date_interval_create_from_date_string("$model->kln_krs_lama days"));
$model->kln_krs_lama = date_format($Dkrs, 'Y-m-d');

$Duts = date_create($model->kln_uts);
$Duts = date_add($Duts, date_interval_create_from_date_string("$model->kln_uts_lama days"));
$model->kln_uts_lama = date_format($Duts, 'Y-m-d');

$Duas = date_create($model->kln_uas);
$Duas = date_add($Duas, date_interval_create_from_date_string("$model->kln_uas_lama days"));
$model->kln_uas_lama = date_format($Duas, 'Y-m-d');


?>

<div class="col-lg-12">
    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		[
			'label'=>'Program',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->pr->pr_nama,
		], 
		[
			'label'=>'Jurusan',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->jr->jr_nama,
		], 
		[
			'label'=>'Kurikulum',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kr_kode." | ".$model->kr->kr_nama,
		], 
		'kln_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Sesi...']],	
		'kln_krs'=>[
			'type'=> Form::INPUT_WIDGET,
			'widgetClass'=>DateControl::classname(),
			'value'=>date('Y-m-d'),
			'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]
		], 
		'krs_akhir'=>[
			'type'=> Form::INPUT_WIDGET,
			'value'=>$Dkrs,
			'widgetClass'=>DateControl::classname(),'options'=>[				
				'type'=>DateControl::FORMAT_DATE,
				'displayFormat'=>'php:Y-m-d',
				
			]
		], 
		
		'kln_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
			'options'=>[
				'type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',
			]
		], 
		
		'kln_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		'uts_akhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
		
		'kln_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		'uas_akhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
		
    ]


    ]);
   ?>
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    <?php
	 ActiveForm::end(); 
	?>
    
</div>
