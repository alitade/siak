<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;


$this->title = 'Ujian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ujian-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
			'{export}',
	        '{toggleData}',
	    ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//			'Id',
			[
				'attribute'=>'Kat',
				'value'=>function($model){
					$kat=[1=>'Reguler','UTS','UAS'];
					return $kat[$model->Kat];
					
				},
				'filter'=>['1'=>'reguler','UTS','UAS']
				
			],
//			'Id',
//			'IdJadwal',
//			'Jrs',
//			'Prg',
            'Dsn',
			[
				'attribute'=>'Tgl',
				'value'=>function($model){
					return \app\models\Funct::TANGGAL($model->Tgl);
				}
			],
			
			[
				'attribute'=>'Jadwal'
			],
			[
				'attribute'=>'Mtk',
				'format'=>'raw',
				'value'=>function($model){
					return $model->Mtk." (".\app\models\Funct::MtkUjian($model->Id).")";	
				}
			],
			'Jml',
//			'Sisa',
            'RgKode',
//            'GKode',
			[
				'label'	=>'Peserta',
				'format'=>'raw',
				'value'	=>function($model){
					return Html::a('cetak Absen',['cetak-absensi','id'=>$model->Id,'jenis'=>3]);
				}
				
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template'=>'{update}'
			],
        ],
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> Ujian',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]); ?>

</div>
