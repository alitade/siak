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
			'value'		=> @function($model){return $model->jr_jenjang."&nbsp;".$model->jr_nama;},
			'format'=>'raw',
			'group'=>true,  // enable grouping,
			'groupedRow'=>true,                    // move grouped column to a single grouped row
			'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
			'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
		],			
		[
			'label'	=> 'Program',
			'value'		=> @function($model){return @$model->pr_nama;},
			'group'=>true,  // enable grouping
			'subGroupOf'=>1, // supplier column index is the parent group,
			'pageSummary'=>'Total',
		],			
		[
			'label'=>'Jadwal',
			'value'=>function($model){
				return @app\models\Funct::HARI()[@$model->jdwl_hari].", ".$model->jadwal;
			},
		],
		[
			'label'=>'Kelas',
			'value'=>function($model){
				return $model->jdwl_kls;
			},
			'width'=>'10px',
			'contentOptions'=>['class'=>'row col-xs-1',],
		],
		[
			'label'=>'Matakuliah',
			'value'=>function($model){return Html::decode($model->mtk_nama);}
		],
		[
			'label'=>'SKS',
			'value'=>function($model){
				return Html::decode($model->bn->mtk->mtk_sks);
			},
			'pageSummary'=>true,
			
		],
		[
			'label'=>'Dosen',
			'value'=>function($model){return $model->ds_nm;}
			
		],
		[
			'attribute'=>'jumabs',
			'format'=>'raw',
			'header'=>'<i class="glyphicon glyphicon-user"></i>',
			'value'=>function($model){
				return $model->jum;
			},
		],
		[
			'attribute'=>'TotSesi',
			'format'=>'raw',
			'header'=>'&sum;Sesi',
			'width'=>'5%',
			'value'=>function($model){
				return $model->TotSesi;
			},
		],

	],
	'condensed'=>true,
	'striped'=>true,
	'showPageSummary'=>true, 
	'panel'=>[
		'heading'=>false,
		'footer'=>false,
		'header'=>false,
	]
]);