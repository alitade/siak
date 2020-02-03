<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-absen-index">
    <?php 
	$form = ActiveForm::begin(); 
	echo
	Form::widget([
		'formName' =>'rekap',
		'form' => $form,
		'columns' => 3,
		'attributes' => [
			'kalender'=>[
				'label'=>'Tahun Akademik',
				'options'=>[
					'placeholder'=>'...'
				],
				'value'=>$_POST['rekap']['kalender'],
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => 
						ArrayHelper::map(app\models\Kalender::find()->all(),'kr_kode',
							function($model,$defaultValue){
								//print_r($model->kr->kr_nama);die();
								return $model->kr->kr_kode." : ".$model->kr->kr_nama;
							}		
						),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],
			], 
			'awal'=>[
				'label'=>'Tanggal Awal',
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
				'options'=>[
					'type'=>2,
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
					],
					'select2Options'=>	[
						'pluginOptions'=>['allowClear'=>true]
					],
					'pluginOptions' => [
							'depends'		=>	['rekap-kalender'],
							'url' 			=> 	Url::to(['/rekap-absen/awal?t=1']),
							'loadingText' 	=> 	'Loading...',
					],
				],
			], 
			'akhir'=>[
				'label'=>'Tanggal Akhir',
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
				'options'=>[
					'type'=>2,
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
					],
					'select2Options'=>	[
						'pluginOptions'=>['allowClear'=>true]
					],
					'pluginOptions' => [
							'depends'		=>	['rekap-kalender'],
							'url' 			=> 	Url::to(['/rekap-absen/awal?t=2']),
							'loadingText' 	=> 	'Loading...',
					],
				],
			], 
			'jr'=>[
				'label'=>'Jurusan',
				'options'=>[
					'placeholder'=>'...'
				],
				'value'=>$_POST['rekap']['jr'],
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => 
						ArrayHelper::map(app\models\Jurusan::find()->all(),'jr_id',
							function($model,$defaultValue){
								//print_r($model->kr->kr_nama);die();
								return $model->jr_jenjang.' '.$model->jr_nama;
							}		
						),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' => true
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],
			], 
			'pr'=>[
				'label'=>'Program',
				'value'=>$_POST['rekap']['pr'],
				'options'=>[
					'placeholder'=>'...'
				],
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => 
						ArrayHelper::map(app\models\Program::find()->all(),'pr_kode',
							function($model,$defaultValue){
								//print_r($model->kr->kr_nama);die();
								return $model->pr_nama;
							}		
						),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' => true
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],
			], 
	
		]
	]);	
	echo "<br />".Form::widget([
		'formName' =>'rekap',
		'form' => $form,
		'columns' => 1,
		'attributes' => [
			[
				'label'	=>false,
				'type'	=>Form::INPUT_RAW,
				'value'	=>
					Html::submitButton('Cari',['class'=>'btn btn-primary'])
					." ".Html::submitButton('Export Excell',['class'=>'btn btn-primary','name'=>'ex'])
			],
			'awal_'=>[
				'label'	=>false,
				'type'	=>Form::INPUT_HIDDEN,
				'value'	=>$tgl[0],
			],
			'akhir_'=>[
				'label'	=>false,
				'type'	=>Form::INPUT_HIDDEN,
				'value'	=>$tgl[1],
			],
		]
	
	
	]);	
	ActiveForm::end();
	?><br /><br />
	<?=($table!=''?$table:'')?>
</div>