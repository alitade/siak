<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
?>
<div class="col-lg-12">
    <?php 
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
			'items'=> app\models\Funct::JURUSAN(1,($subAkses?['jr_id'=>$subAkses['jurusan']]:"")),
			'options'=>[
				'placeholder'=>'Enter Jr ID...',
				'inline'=>true,
			],
			
		] ,
		'kr_kode'=>[
            'label'=>'Kurikulum',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::KR(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		//'kln_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Stat...']], 
		'kln_sesi'=>['label'=>'Pertemuan per Minggu','type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln Sesi...']],
		[
            'label'=>'Perwalian',
            'columns'=>2,
            'attributes'=>[
                'kln_krs'=>['type'=> Form::INPUT_WIDGET,'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
                'krs_akhir'=>['type'=> Form::INPUT_WIDGET,'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
            ]
        ],
        [
            'label'=>'Perkuliahan',
            'columns'=>2,
            'attributes'=>['kln_masuk'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],],
        ],
        [
            'label'=>'UTS',
            'columns'=>2,
            'attributes'=>[
                'kln_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
                'uts_akhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
            ],
        ],
        [
            'label'=>'UAS',
            'columns'=>2,
            'attributes'=>[
                'kln_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
                'uas_akhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',]],
            ],
        ],
    ]


    ]);
   ?>
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    <?php
	 ActiveForm::end(); 
	?>
</div>
    
