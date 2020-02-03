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
	//'rowOptions'=>function($data){return ['style'=>'color:red'];},
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
			'label'	=> 'Program',
			'value'		=> @function($model){return @$model->pr->pr_nama;},
		],
		[
			'label' => 'Nama',
			//'attribute'	=> 'pr_kode',
			'value'		=> @function($model){return @$model->Nama;},
			
		],
		[
			'label'=>'NPM',
			'value'=>function($model){ return $model->mhs_nim;}
		],
		[
			'label'=>'Angkatan',
			'value'=>function($model){ return $model->mhs_angkatan;}
		],
		[
			'label'	=> 'Dosen Wali',
			'value'		=> function($model){return @app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali];},
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