<?php
use yii\helpers\Html;
use kartik\grid\GridView;
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'toolbar'=>false,
	'columns' => [
		['class' => 'kartik\grid\SerialColumn'],
			'kr_kode',
		[
			'attribute'	=> 'jr_id',
			'value'		=> @function($model){return $model->jr_jenjang." ".$model->jr_nama;},
		],			
		[
			'attribute'	=> 'pr_kode',
			'value'		=> @function($model){return @$model->pr_nama;},
			'pageSummary'=>'Total',
		],			
		[
			'attribute'=>'jdwl_kls',
			'contentOptions'=>['class'=>'row col-xs-1',],
		],
		[
			'attribute'=>'mtk_nama',
			'value'=>function($model){return Html::decode($model->mtk_nama);}
			
		],
		'ds_nm',
		[
			'label'=>'SKS',
			'value'=>function($model){
				return Html::decode($model->bn->mtk->mtk_sks);
			},
			'pageSummary'=>true,
			
		],
		[
			'attribute'=>'rg_kode',
			'value'		=> function($model){return $model->rg->rg_nama;},
		],	
		[
			'attribute'=>'jum',
			'format'=>'raw',
			'header'=>'<i class="glyphicon glyphicon-user"></i>',
			'pageSummary'=>true,

		],
		[
			'header'=>'<center>Status<br />Nilai</center>',
			'mergeHeader'=>true,
			'format'=>'raw',
			'value'=>function($model){
				return "<center>".(\app\models\Funct::StatNilDos($model->jdwl_id)>0 ? 
				'<i class="glyphicon glyphicon-ok"></i></i>':
				'<i class="glyphicon glyphicon-remove"></i>')."</center>";
			},
			
		],
		[
			'class'=>'kartik\grid\ActionColumn',
			'template'=>
				'
							<li>{hps}</li>
							<li>{add}</li>
							<li>{grp}</li>
							<li>{ung}</li>
							<li>{dtl}</li>
							<li>{edt}</li>
							<li>{a_abs}</li>
							<li>{c_uts}</li>
							<li>{c_uas}</li>
							<li>{c_abs}</li>
				'
			,
			'buttons' => [
				'add'=> function ($url, $model, $key) {
						return Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Jadwal',['akademik/ajr-view','id' => $model->bn_id]);
				},


				'grp'=> function ($url, $model, $key) {
						return Html::a('<i class="glyphicon glyphicon-link"></i> Group',['akademik/jdw-group','id' => $model->jdwl_id]);
				},

				'ung'=> function ($url, $model, $key) {
						//return Html::a('<i class="glyphicon glyphicon-remove"></i> '.$model->GKode,['akademik/ungroup','id' => $model->jdwl_id]);
						return (empty($model->GKode) ? 
							false:
							Html::a('<i class="glyphicon glyphicon-remove"></i> Ungroup',['akademik/ungroup','id' => $model->jdwl_id])
						);
					},

				'hps'=> function ($url, $model, $key) {
						return ($model->jum > 0 ? 
							false :
							Html::a('<i class="glyphicon glyphicon-trash"></i> Hapus',['akademik/jdw-delete','id' => $model->jdwl_id])
						);
					},
				'dtl'=> function ($url, $model, $key) {
						return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail'
						,['akademik/jdw-update','id' => $model->jdwl_id,'view'=>'t']);
					},
				'edt'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Edit Absen',
							['akademik/jdw-view','id' => $model->jdwl_id,'view'=>'t']
						);
					},
				'a_abs'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Input Absen',
							['bisa/absensi','id' => $model->jdwl_id, 'matakuliah'=>$model->mtk_kode, 'sort'=>'id']
						);
					},
				'c_abs'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Cetak Absen Harian',
							['akademik/cetak-absen','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
				'c_uts'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Cetak Absen UTS',
							['akademik/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>1]
						);
					},
				'c_uas'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Cetak Absen UAS',
							['akademik/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
					
			],
			'dropdown'=>true,
			'dropdownOptions'=>['class'=>'pull-right'],
			'headerOptions'=>['class'=>'kartik-sheet-style'],
		],
	],
	'responsive'=>true,
	'hover'=>true,
	'condensed'=>true,
	'showPageSummary'=>true, 
	'panel'=>false
]);
?>