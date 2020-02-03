<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\KalenderSearch $searchModel
 */

$this->title = 'Kalender Akademik';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kalender-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-kalender-akademik'],['class'=>'btn btn-info'])
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
				//'attribute'=>'kln_krs',
				'label'=>'KRS',
				'format'=>'raw',
				'value'=>function($model){
					$date=date_create($model->kln_krs);
					date_add($date,date_interval_create_from_date_string("$model->kln_krs_lama days"));
					return Funct::TANGGAL($model->kln_krs)." <br /> ".Funct::TANGGAL(date_format($date,"Y-m-d"));
				}
			],
            [
				//'attribute'=>'kln_krs',
				'label'=>'Perkuliahan',
				'format'=>'raw',
				'value'=>function($model){
					return Funct::TANGGAL($model->kln_masuk);
				}
			],
            [
				//'attribute'=>'kln_krs',
				'label'=>'UTS',
				'format'=>'raw',
				'value'=>function($model){
					$date=date_create($model->kln_uts);
					date_add($date,date_interval_create_from_date_string("$model->kln_uts_lama days"));
					return Funct::TANGGAL($model->kln_uts)." <br /> ".Funct::TANGGAL(date_format($date,"Y-m-d"));
				}
			],
            [
				//'attribute'=>'kln_krs',
				'label'=>'UAS',
				'format'=>'raw',
				'value'=>function($model){
					$date=date_create($model->kln_uas);
					date_add($date,date_interval_create_from_date_string("$model->kln_uas_lama days"));
					return Funct::TANGGAL($model->kln_uas)." <br /> ".Funct::TANGGAL(date_format($date,"Y-m-d"));
				}
			],
            [
                'class' => 'kartik\grid\ActionColumn',
				'template'=>'{view}',
                'buttons' => [
					'view' => function ($url, $model) {
							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
							Yii::$app->urlManager->createUrl(
								['prodi/kln-view','id' => $model->kln_id,'view'=>'t']),['title' => Yii::t('yii', 'View'),]
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
        	'heading'=>'<i class="fa fa-navicon"></i> Kalender Akademik',
    	]
    ]); Pjax::end(); ?>

</div>
