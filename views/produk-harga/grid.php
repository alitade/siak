<?php
use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php 
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		[
			'attribute'=>'kode_produk',
			'value'=>function($model)use($hideProd){
				return $model->kode_produk;
			},
			'visible'=>($hideProd?false:true)
		],
		[
			'attribute'=>'harga',
			'value'=>function($model){
				return number_format($model->harga)." IDR" ;
			},
			'format'=>'raw',
		],
		[
			'attribute'=>'aktif',
			'value'=>function($model){
				if($model->aktif=='1'){return '<div class="label label-success">Aktif</div>';}
				return Html::a('Aktifkan',['/produk-harga/aktif','id'=>$model->kode_produk,'h'=>$model->harga],['class'=>'btn btn-primary']);
			},
			'filter'=>['N','Y'],
			'format'=>'raw',
		],
	],
	'responsive'=>false,
	'hover'=>true,
	'condensed'=>true,
	'floatHeader'=>false,
	'panel' => [
		'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Harga Produk '.(@$model->produk?$model->produk:'').'</h3>',
		'type'=>'info',
		'before'=>(hideAdd?"":Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success'])),
		'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
		'showFooter'=>false
	],
]); 
