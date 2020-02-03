<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BankSoalSearch $searchModel
 */

$this->title = 'Bank Soal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-soal-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[	
				'attribute'=>'mtk_kode',
				'value'=>function($model){return $model->mtk_kode.' : '.$model->mk->mtk_nama;	},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::MTK(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
			],
			[
				'attribute'=>'jenis',
				'value'=>function($model){
					$jns='-';
					if($model->jenis=='1'){$jns='Essay';}
					if($model->jenis=='0'){$jns='PG';}
					return $jns;
				},
				'width'=>'1%',
				'filter'=>['PG','Essay']
			],
			[
				'attribute'=>'jml_soal',
				'width'=>'1%',
			],
            [
                'class' => 'kartik\grid\ActionColumn',
				'template'=>'{update} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-download"></span>', 
						Yii::$app->urlManager->createUrl(['bank-soal/download','id' => $model->Id,
					'edit'=>'t']), ['title' => Yii::t('yii', 'Edit'),
					]);}
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
	