<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>
<p> </p>
<div class="mahasiswa-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            [
				'content'=>
                #Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
	            Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to().'&c=1',['class'=>'btn btn-info','target'=>'_blank'])	
            ],
	        '{toggleData}',
			(!Funct::acc('gridview')?false:"{export}"),
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'	=> 'jr_id',
                'width'=>'20%',
                'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
				'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ]   ,
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
			[
                'attribute'	=> 'pr_kode',
                'width'=>'10%',
                'value'		=> @function($model){return @$model->pr->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::PROGRAM(),
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
                    return $model->mhs_nim;
                    return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
                }
            ],

            'mhs_angkatan',
			[
				'label'=>'Kurikulum',
				'attribute'=>'thn',
                'width'=>'20%',
			],

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
                        if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail',['/mhs/view','id' => $model->mhs_nim]);
                    },
                    'CtkTrans'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/cetak/cetak')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-print"></span> Cetak Transkrip Sementara',
                            ['/transkrip/cetak/cetak','id' => $model->mhs_nim,]
                        );
                    },
                    'export'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/pindah')){return false;}
                        if($model->ws){return false;}
                        return Html::a('<i class="glyphicon glyphicon-list"></i> Export Nilai'
                            ,['/transkrip/nilai/pindah','id' => $model->mhs_nim,'view'=>'t']);
                    },
                    'konversi'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/konversi')){return false;}
                        if($model->ws){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span> Konversi',
                            ['/transkrip/nilai/konversi','id' => $model->mhs_nim,]
                        );
                    },
                    'transkrip'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/wisuda/transkrip')){return false;}
                        if(!$model->wisuda->id){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span> Transkrip',
                            ['/transkrip/wisuda/transkrip','id' => $model->wisuda->id,]
                        );
                    },
                    'urgent'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/nilai-urgent')){return false;}
                        if($model->ws){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span>Nilai Prodi',
                            ['/transkrip/nilai/nilai-urgent','id' => $model->mhs_nim,]
                        );
                    },

                ],
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> '.$this->title,
    ]
    ]); ?>

</div>
