<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\MahasiswaSearch $searchModel
 */

$this->title = 'Dosen Wali';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-index">
<div class="page-header">
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,

	    'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-list"></i> Daftar Dosen Wali', ['dsn-create'],['class'=>'btn btn-success']).''.
				//Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['dsn-create'],['class'=>'btn btn-success']).''.
	            Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-dosen-wali'],['class'=>'btn btn-info'])
	        ],
	        '{toggleData}',
	    ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'	=>'jr_id',
				'label'=>'Jurusan',
				'width'=>'20%',
				'value'	=> @function($model){return @app\models\Funct::JURUSAN()[$model->jr_id];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Jurusan-'],
			],
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
				'attribute'	=>'ds_id',
				'label'=>'Dosen Wali',
				'value'		=> @function($model){return @app\models\Funct::DSN(1,'ds_id')[$model->ds_id];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(1,'ds_id'), 
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
							['akademik/dsn-wali','id' => $model->ds_id,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},

/*                'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/dsn-wali','id' => $model->ds_id,'edit'=>'t']),['title' => Yii::t('yii', 'Edit'),]
						);
					},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/dsn-wali','id' => $model->ds_id,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
						);
					},
*/
                ],
            ],


        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i>Dosen Wali',
			
    	]
    ]); Pjax::end(); ?>

</div>
