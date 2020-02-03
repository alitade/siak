<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div class="kalender-form">
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Kalender Akademik</div>
    	<div class="panel-body">
        	<div class="col-lg-12">
    <?php 
	echo print_r($model->getErrors());
	
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'pr_kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::PROGRAM(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'jr_id'=>[
			'type'=> Form::INPUT_CHECKBOX_LIST, 
			'items'=>app\models\Funct::JURUSAN(),
			'options'=>[
				'placeholder'=>'Enter Jr ID...',
				'inline'=>true,
			],
			
		] ,
		'kr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kode...']], 
		//'kln_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Stat...']], 
		'kln_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Sesi...']], 
	
		'kln_krs'=>[
			'type'=> Form::INPUT_WIDGET,
			'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]
		], 
		'kln_krs_lama'=>[
			'type'=> Form::INPUT_WIDGET,
			'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]
		], 
		
		'kln_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
			'options'=>[
				'type'=>DateControl::FORMAT_DATETIME,'displayFormat'=>'php:Y-m-d H:i:s',
			]
		], 
		
		'kln_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		'kln_uts_lama'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		
		'kln_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		'kln_uas_lama'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]], 
		
		//'kln_krs_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Krs Lama...']], 
		//'kln_uts_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Uts Lama...']], 
		//'kln_uas_lama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Uas Lama...']], 
    ]


    ]);
   ?>
    		</div>
    	</div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
    <?php
	 ActiveForm::end(); 
	?>
    
</div>
