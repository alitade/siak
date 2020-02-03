<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
$this->title = 'Kurikulum';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kurikulum-index">
	<p></p>
    <?php 
	Pjax::begin(['enablePushState'=>false]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
	        ['content'=>
				(!Funct::acc('/kurikulum/create')?"":Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['/kurikulum/create'],['class'=>'btn btn-success']))
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
						if(!Funct::acc('/kurikulum/view')){return false;}
						
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['/kurikulum/view','id' => $model->kr_kode]),['title' => Yii::t('yii', 'Detail'),]
						);
						},
					'update' => function ($url, $model) {
						if(!Funct::acc('/kurikulum/update')){return false;}
							return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
							Yii::$app->urlManager->createUrl(
								['/kurikulum/update','id' => $model->kr_kode]),['title' => Yii::t('yii', 'Edit'),]
							);
						},
					'delete' => function ($url, $model) {
						if(!Funct::acc('/kurikulum/delete')){return false;}
							return Html::a('<span class="glyphicon glyphicon-trash"></span>',
							Yii::$app->urlManager->createUrl(
								['/kurikulum/delete','id' => $model->kr_kode]),['title' => Yii::t('yii', 'Hapus'),'onClick'=>"return confirm('Hapus Data ".$model->kr_kode."')"]
							);
						},
                ],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'floatHeader'=>false,
        'condensed'=>true,
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> '.$this->title,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]); 
	Pjax::end(); ?>

</div>
