<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\MahasiswaSearch $searchModel
 */

$this->title = 'Daftar Dosen Wali';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,

	    'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-dosen-wali'],['class'=>'btn btn-info'])
	        ],
	        '{toggleData}',
	    ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'	=>'pr_kode',
				'label'=>'Program',
				'width'=>'20%',
				'value'	=> @function($model){return @app\models\Funct::PROGRAM()[$model->pr_kode];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'- Program - '],
			],
			[
				'attribute'	=>'ds_nidn',
				'label'=>'Dosen Wali',
				'value'		=> @function($model){return @app\models\Funct::DSN()[$model->ds_id];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(4), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'- Dosen Wali -'],
			],
			[
				'attribute'	=>'tot',
				'width'=>'10%',
				'label'=>'Total Mahasiswa',
			],
            [
                'class' => 'kartik\grid\ActionColumn',
				'width'=>'5%',
				'template'=>'{view}',
                'buttons' => [

                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['prodi/dsn-wali','id' => $model->ds_id,'p'=>$model->pr_kode,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
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
        	'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['dsn'], ['class' => 'btn btn-info']),
			'heading'=>'<i class="fa fa-navicon"></i>Daftar Dosen Wali',
			
    	]
    ]); Pjax::end(); ?>

</div>
