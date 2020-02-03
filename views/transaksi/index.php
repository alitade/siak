<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\model\Funct;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Faktur Vakasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-index">
    <div style="font-weight:bold;padding:5px;margin:5px;font-size:14px;border:solid 1px #000;"><i>
    <ul>
        <li>Faktur yang sudah tercetak tidak bisa diubah / dihapus</li>
    </ul>
    </i></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'tgl',
				'label'=>'tanggal',
				'value'=>function($model){
					$tgl = explode(" ",$model->tgl);
					return \app\models\Funct::TANGGAL($tgl[0]).', '.$tgl[1];
				}
			],

			[
				'attribute'=>'kode_transaksi',
				'label'=>'Faktur',
				'format'=>'raw',
				'value'=>function($model){
					if($model->status==0){return $model->kode_transaksi;}
					return Html::a('<i class="fa fa-eye"></i> '.$model->kode_transaksi,
						['/pengajar/vakasi-faktur-view','id'=>$model->kode_transaksi],
						['class'=>'btn btn-primary btn-xs','style'=>'font-size:14px','target'=>'_blank']
					);
				},
			],
			[
				'attribute'=>'ds_id',
				'label'=>'Dosen',
				'value'=>function($model){
					return $model->dsn->ds_nm;
				},
			],
			[
				'attribute'=>'cuid',
				'label'=>'Operator',
				'value'=>function($model){
					return $model->c->name;
				},
			],
			[
				'attribute'=>'status',
				'label'=>'Satus',
				'format'=>'raw',
				'value'=>function($model){
					if($model->status==0){
						return Html::a('DRAFT',['/pengajar/vakasi-faktur','id'=>$model->kode_transaksi],['class'=>'btn btn-primary']);
					}
					return '';
				},
			],
			[
				'attribute'=>'cetak',
				'header'=>'<i class="fa fa-print"></i>',
				'format'=>'raw',
				'value'=>function($model){
					return $model->cetak;
				},
			],
			
           	[
				'class' => 'kartik\grid\ActionColumn',
				'template'=>'{update} {delete}',
				#'dropdown'=>true,
				#'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'buttons'=>[
					'update'=> function ($url, $model, $key) {
						if($model->cetak){return false;}
						return Html::a('<i class="fa fa-pencil"></i>',['/pengajar/vakasi-faktur','id'=>$model->kode_transaksi]);
					},
					'delete'=> function ($url, $model, $key) {
						#if($model->cetak){return false;}
						return Html::a('<i class="fa fa-trash"></i>',['/pengajar/vakasi-del-draft','id'=>$model->kode_transaksi]);
					},
				
				]
				
				
			
			],
        ],
    ]); ?>
</div>
