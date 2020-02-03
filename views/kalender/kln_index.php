<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
$this->title = 'Kalender Akademik';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kalender-index">
    <p></p>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
	        ['content'=>
                (Funct::acc('/kalender/create')?Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['/kalender/create'],['class'=>'btn btn-success']):"")." ".
                (Funct::acc('/kalender/pdf')? Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['/kalender/pdf'],['class'=>'btn btn-info']):"")
	        ],
	        '{toggleData}',
	    ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'kr_kode',
				'width'=>'10%',
			],
			[
				'attribute'=>'jr_id',
				'value'=>function($model){return $model->jr->jr_jenjang." ".$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
			],
			[
				'attribute'=>'pr_kode',
				'value'=>function($model){return $model->pr->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
			],
            [
				'label'=>'KRS',
				'format'=>'raw',
				'value'=>function($model){return Funct::TANGGAL($model->kln_krs)." <br /> ".Funct::TANGGAL($model->krs_akhir);}
			],
            [
				'label'=>'Perkuliahan',
				'format'=>'raw',
				'value'=>function($model){return Funct::TANGGAL($model->kln_masuk);}
			],
            [
				//'attribute'=>'kln_krs',
				'label'=>'UTS',
				'format'=>'raw',
				'value'=>function($model){
					return Funct::TANGGAL($model->kln_uts)." <br /> ".Funct::TANGGAL($model->uts_akhir);
				}
			],
            [
				//'attribute'=>'kln_krs',
				'label'=>'UAS',
				'format'=>'raw',
				'value'=>function($model){
					return Funct::TANGGAL($model->kln_uas)." <br /> ".Funct::TANGGAL($model->uas_akhir);
				}
			],

            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
	                'update' => function ($url, $model) {
                        if(!Funct::acc('/kalender/update')){ return false;}
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['/kalender/update','id' => $model->kln_id]),['title' => Yii::t('yii', 'Edit'),]);
					},
					'view' => function ($url, $model) {
                        if(!Funct::acc('/kalender/view')){ return false;}
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',Yii::$app->urlManager->createUrl(['/kalender/view','id' => $model->kln_id]),['title' => Yii::t('yii', 'View'),]);
                    },
					'delete' => function ($url, $model) {
                        if(!Funct::acc('/kalender/delete')){ return false;}
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
							Yii::$app->urlManager->createUrl(['/kalender/delete','id' => $model->kln_id]),['title' => Yii::t('yii', 'Hapus'),'onClick'=>"return confirm('Hapus Data Ini?')"]);
					},

                ],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>false,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> '.$this->title,
    	]
    ]); Pjax::end(); ?>

</div>
