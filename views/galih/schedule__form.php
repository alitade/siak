<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
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
<div class="panel panel-primary">
    <div class="panel-heading">Input Jadwal</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
		'type'=>ActiveForm::TYPE_HORIZONTAL,
		'fieldConfig'=>[
			'autoPlaceholder'=>true,
		]
	]); 
	
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 6,
    'attributes' => [
		    'jdwl_hari'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>$hari,
				'options'=>['placeholder'=>'Hari',]
			],
        ],  
    	'jdwl_masuk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Masuk']], 
    	'jdwl_keluar'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Keluar']], 
        'rg_kode'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>$ruang,
				'options'=>['placeholder'=>'Ruangan',]
			], 
        ],	
    	'jdwl_kls'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kelas']], 
		'action'=>[
			'type'=> Form::INPUT_RAW, 
			'value'=>
				Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
			,
			'options'=>[
				'placeholder'=>'Kelas'
			]
		], 
        
    	
    ]


    ]);
?>
    </div>
    </div>    
</div>

<?php ActiveForm::end(); ?>
