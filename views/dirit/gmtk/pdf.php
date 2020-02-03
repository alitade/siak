<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;

//Pjax::begin(); 
$dataProvider->pagination=false;
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'layout'=>'{items}',
	'toolbar'=>false,
	'columns' => [
		[
			'class' => 'kartik\grid\SerialColumn',
			'width'=>'10px',
		],
		[
			'label'	=> 'Jurusan',
			'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
		],
		[
			'label' => 'Matakuliah',
			//'attribute'	=> 'pr_kode',
			'value'		=> @function($model){return @$model->mtk_kode.': '.$model->mtk_nama;},
			
		],
		[
			'label'=>'SKS',
			'value'=>function($model){ return $model->mtk_sks;}
		],
		[
			'label'=>'Semester',
			'value'=>function($model){ return $model->mtk_semester;}
		],
	],
	'condensed'=>true,
	'striped'=>true,
	'panel'=>[
		'heading'=>false,
		'footer'=>false,
		'header'=>false,
	]
]);