<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\MahasiswaSearch $searchModel
 */

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-index">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['mhs-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
			],
            [
                'attribute' => 'Nama',
                'width'=>'20%',
            ],
            [
                'attribute' => 'mhs_nim',
				'format'=>'raw',
				'value'=>function($model){
					return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
				}
            ],
			
            'mhs_angkatan',
//            'mhs_stat', 
			[
				'attribute'	=> 'ds_wali',
                'width'=>'20%',
				'value'		=> function($model){return @app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(1,'ds_id'), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'- Dosen Wali -'],
			],
			[
				'attribute'	=> 'ws',
                'width'=>'5%',
				'format'=>'raw',
				'value'		=> function($model){
						return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
					},
				'filter'=>['N','Y'], 
				
			],

			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>
					'
								<li>{view}</li>
								<li>{export}</li>
								<li>{konversi}</li>
								<li>{transkrip}</li>
								<li>{CtkTrans}</li>
								<li>{urgent}</li>
					'
				,
				'buttons' => [
					'view'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail',['/akademik/mhs-view','id' => $model->mhs_nim]);
					},
					'CtkTrans'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-print"></span> Cetak Transkrip Sementara',
								['cetak/cetak','id' => $model->mhs_nim,]
							);
						},
					'export'=> function ($url, $model, $key) {
							if($model->ws){return false;}
							return Html::a('<i class="glyphicon glyphicon-list"></i> Export Nilai'
							,['pindah','id' => $model->mhs_nim,'view'=>'t']);
						},
					'konversi'=> function ($url, $model, $key) {
							if($model->ws){return false;}
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Konversi',
								['konversi','id' => $model->mhs_nim,]
							);
						},
					'transkrip'=> function ($url, $model, $key) {
							if(!$model->wisuda->id){return false;}
						
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Transkrip',
								['wisuda/transkrip','id' => $model->wisuda->id,]
							);
						},
					'urgent'=> function ($url, $model, $key) {
							if($model->ws){return false;}
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span>Nilai Prodi',
								['nilai-urgent','id' => $model->mhs_nim,]
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
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Mahasiswa',
        ]
    ]); ?>

</div>
