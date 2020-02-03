<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

use app\models\Funct;
//die(print_r(Funct::DataWali()));
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\MahasiswaSearch $searchModel
 */

$this->title = 'Daftar Dosen Wali';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wali-index">
    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['showLabels'=>false]]); 
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [
		'KrKd'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::DataWali(),
                'options' => [
                    'fullSpan'=>1,
                    'placeholder' => 'Pilih Kurikulum',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
			'width'=>'10%'
		], 
		'JrId'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
            'options'=>[
				'type'=>2,
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Jurusan',
                ],
				'select2Options'=>	[
					'pluginOptions'=>['allowClear'=>true]
				],
				'pluginOptions' => [
						'depends'		=>	['wali-krkd'],
						'url' 			=> 	Url::to(['/akademik/dropwali']),
						'loadingText' 	=> 	'Loading...',
				],
            ],
		], 
		'DsId'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
            'options'=>[
				'type'=>2,
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Dosen',
                ],
				'select2Options'=>	[
					'pluginOptions'=>['allowClear'=>true]
				],
				'pluginOptions' => [
						'depends'		=>	['wali-jrid'],
						'url' 			=> 	Url::to(['/akademik/dropwalids']),
						'loadingText' 	=> 	'Loading...',
				],
            ],
		], 
		[
			'type'	=> Form::INPUT_RAW,
			'value' => Html::submitButton($model->isNewRecord ? Yii::t('app', 'Tambah') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
		
		],

    ]


    ]);
    ActiveForm::end(); ?>



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
				'attribute'	=>'KrKd',
				'width'=>'5%',
			],
			[
				'attribute'	=>'JrId',
				'label'=>'Jurusan',
				'width'=>'20%',
				'value'	=> @function($model){return @app\models\Funct::JURUSAN()[$model->JrId];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Jurusan-'],
			],
			[
				'attribute'	=>'DsId',
				'label'=>'Dosen Wali',
				'value'		=> @function($model){return @app\models\Funct::DSN(1,'ds_id')[$model->DsId];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(1,'ds_id'," ds_id in(select distinct DsId from tbl_wali)"), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'- Dosen Wali -'],
			],
            [
                'class' => 'kartik\grid\ActionColumn',
				'width'=>'5%',
				'template'=>'{view}',
                'buttons' => [

                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/dsn-wali','id' => $model->DsId,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
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
			'heading'=>'<i class="fa fa-navicon"></i>Daftar Dosen Wali',
			
    	]
    ]); Pjax::end(); ?>

</div>
