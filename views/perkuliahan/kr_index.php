<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Kurikulum';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kurikulum-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php 
	Pjax::begin(['enablePushState'=>false]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['kr-create'],['class'=>'btn btn-success']).''.""
	            //Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-matakuliah'],['class'=>'btn btn-info'])
	        ],
	        '{toggleData}',
	    ],

        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
	            'kr_kode',
	            'kr_nama',
            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/kr-view','id' => $model->kr_kode,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/kr-update','id' => $model->kr_kode,'edit'=>'t']),['title' => Yii::t('yii', 'Edit'),]
						);
					},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/kr-delete','id' => $model->kr_kode,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
						);
					},
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'floatHeader'=>true,
        'condensed'=>true,
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i>'.$this->title,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]); 
	Pjax::end(); ?>

</div>
