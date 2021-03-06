<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\Mata Kuliahearch $searchModel
 */

$this->title = 'Matakuliah';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="matkul-index">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php 
	Pjax::begin(['enablePushState'=>false]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['mtk-create'],['class'=>'btn btn-success']).''.
	            Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-matakuliah'],['class'=>'btn btn-info'])
	        ],
	        '{toggleData}',
	    ],

        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> function($model){return $model->jr->jr_jenjang." ".$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
            'mtk_kode',
            'mtk_nama',
            'mtk_sks',
//            'mtk_kat',
//            'mtk_stat',
//            'penanggungjawab', 
//            'mtk_sesi', 
			[
				'attribute'=>'mtk_sub',
				'value'=>function($model){
					return @Funct::MTK()[@$model->mtk_sub];
				},
			],
            'mtk_semester', 
			[
				'attribute'=>'mtk_kat',
				'value'=>function($model){
					$a=" ";
					if($model->mtk_kat=='0'){$a=" Teori ";}
					if($model->mtk_kat=='1'){$a=" Praktek ";}
					if($model->mtk_kat=='2'){$a=" Teori + Praktek";}
					return $a;
					;},
				'filter'=>['Teori','Praktek','Teori + Praktek']	
			],

            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [

                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/mtk-view','id' => $model->mtk_kode,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/mtk-update','id' => $model->mtk_kode,'edit'=>'t']),['title' => Yii::t('yii', 'Edit'),]
						);
					},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/mtk-view','id' => $model->mtk_kode,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
						);
					},

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> Matakuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]);Pjax::end(); ?>
</div>