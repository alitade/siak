<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Krs;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="page-header">
        <h4><?php 
			
			echo $ModBn->kln->kr_kode." - ".$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama
			." - ".$ModBn->kln->pr->pr_nama
			."<br />".$ModBn->ds->ds_nm." - ".$ModBn->mtk_kode.': '.$ModBn->mtk->mtk_nama
			." - ".@app\models\Funct::HARI()[$ModJdw->jdwl_hari].", $ModJdw->jdwl_masuk - $ModJdw->jdwl_keluar  ";
		?></h4>
     	<div class="col-sm-12" style="border:solid #000 1px;padding:2px;font-weight:bold">
        	Penggabungan jadwal hanya berlaku untuk jurusan dan program perkuliahan yang berbeda.<br />
            Fasilitas ini berfungsi untuk menyamakan jadwal perkuliahan jadwal yang di pilih ke jadwal tujuan.
     	</div>
        <div style="clear:both"></div>
    </div>
    
	<fieldset><legend> Jadwal Tujuan</legend>
    <?php 	
	
	$form = ActiveForm::begin([
		'id'=>'create-krs',
		'method'=>'post'
    ]);
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar'=>false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label'=>' ',
				'value'=>function($model){
					return Html::radio('jdwl',false,['value' =>$model["jdwl_id"],'id'=>'get']);
				},
				'format'=>'raw'
			],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return $model->jr_jenjang." ".$model->jr_nama;},
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr_nama;},
			],			
			[
				'attribute'=>'jdwl_hari',
				'value'=>function($model){
					return @app\models\Funct::HARI()[@$model->jdwl_hari];
				},
			],
			[
				'attribute'=>'jdwl_masuk',
				'value'=>function($model){return $model->jadwal;},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'jdwl_kls',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
			[
				'attribute'=>'mtk_nama',
				'value'=>function($model){return Html::decode($model->mtk_nama);}
				
			],
			[
				'label'=>'SKS',
				'value'=>function($model){
					return Html::decode($model->bn->mtk->mtk_sks);
				},
			],
			[
				'attribute'=>'rg_kode',
				'width'=>'10%',
				'value'		=> function($model){return $model->rg->rg_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::RUANG(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
				
			],	
			[
				'attribute'=>'jumabs',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i>',
				'width'=>'5%',
				'value'=>function($model){
					return Html::a($model->jum,
					Yii::$app->urlManager->createUrl(
						['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
					);
				},
				
				
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
								<li>{dtl}</li>
								<li>{edt}</li>
								<li>{a_abs}</li>
								<li>{c_uts}</li>
								<li>{c_uas}</li>
								<li>{c_abs}</li>
					'
				,
				'buttons' => [
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
        'panel'=>[
			'after'=>
			Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Gabungkan Jadwal', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave'])
			." ".
			Html::a('<i class="glyphicon glyphicon-remove"></i> Batal',['/akademik/ajr-view','id'=>$ModBn->id], ['class' => 'btn btn-info']),
			'header'=>false,
			'footer'=>false,
		]
    ]);
	ActiveForm::end();
	?>
    
    </fieldset>
</div>
