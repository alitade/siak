<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$tmpt=Ruang::find()->all();
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');
?>

<div class="jadwal-form">
<div class="panel panel-default">
    <div class="panel-heading">Jadwal Kuliah</div>
    <div class="panel-body">
    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]); 

	echo"<h4>Jadwal Tatap Muka</h4>";
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'jdwl_hari'=>[
			'label'=>'Hari',
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>$hari,
				'options'=>[
					'disabled'=>($q['t']>0?true:false),
				]
			],
        ],  
		'jam'=>[
			'label'=>'Jam',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
				'jdwl_masuk'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Masuk','readonly'=>($q['t']>0?true:false),],
					
				], 
				'jdwl_keluar'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Keluar','readonly'=>($q['t']>0?true:false),]
				], 
			],
		],
        'rg_kode'=>[
			'label'=>'Ruangan',
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang], 
        ],	
    ]
    ]);


	echo"<hr><h4>Jadwal UTS</h4>";
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'rg_uts'=>[
			'label'=>'Ruangan',
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang],  
        ],
		'jdwl_uts'=>[
			'label'=>'Tanggal',
			'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
			'options'=>[
				'attribute' => 'jdwl_uts',
				'pluginOptions' => ['format' => 'yyyy-mm-dd',]					
			]
		], 
		'uts'=>[
			'label'=>'Jam',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
				'uts_masuk'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Masuk','readonly'=>($q['t']>0?true:false),],
					
				], 
				'uts_keluar'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Keluar','readonly'=>($q['t']>0?true:false),]
				], 


			],
		]
    ]
    ]);


	echo"<hr><h4>Jadwal UAS</h4>";
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'rg_uas'=>[
			'label'=>'Ruangan',
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang],  
        ],
		'jdwl_uas'=>[
			'label'=>'Tanggal',
			'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
			'options'=>[
				'attribute' => 'jdwl_uas',
				'pluginOptions' => ['format' => 'yyyy-mm-dd',]					
			]
		], 
		'uas'=>[
			'label'=>'Jam',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
				'uas_masuk'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Masuk','readonly'=>($q['t']>0?true:false),],
					
				], 
				'uas_keluar'=>[
					'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Keluar','readonly'=>($q['t']>0?true:false),]
				], 


			],
		]
    ]
    ]);

?>
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
