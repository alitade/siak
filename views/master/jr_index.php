<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JurusanSearch $searchModel
 */

$this->title = 'Jurusan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jurusan-index">
    <?php 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['jr-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-jurusan'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'jr_id',
			[
				'attribute'	=> 'fk_id',
				'width'=>'20%',
				'value'		=> function($model){return $model->fk->fk_id." : ".$model->fk->fk_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::FK(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
            'jr_nama',
            'jr_head',
			[
				'attribute'	=> 'jr_jenjang',
				'filter'=>['S2'=>'S2','S1'=>'S1','D3'=>'D3']
				
			],	
//            'jr_stat', 

            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['master/jr-view','id' => $model->jr_id,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
						Yii::$app->urlManager->createUrl(
							['master/jr-update','id' => $model->jr_id,'edit'=>'t']),['title' => Yii::t('yii', 'Edit'),]
						);
					},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['/master/jr-delete','id' => $model->jr_id]),[
								'title' => Yii::t('yii', 'Hapus'),
								'onClick'=>"return confirm('Hapus data Ini?')",
							]
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
            'heading'=>'<i class="fa fa-navicon"></i> Jurusan',
        ]
    ]); 
	?>

</div>
