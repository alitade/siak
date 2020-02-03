<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\FakultasSearch $searchModel
 */

$this->title = 'Fakultas';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = ['fk_id','fk_nama',];
?>
<div class="fakultas-index">
    <?php 
		Pjax::begin(); 
		echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['fk-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-fakultas'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        // set export properties
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'fk_id',
            'fk_nama',
			'fk_head',
            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['master/fk-view','id' => $model->fk_id,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
					Yii::$app->urlManager->createUrl(['master/fk-update','id' => $model->fk_id,'edit'=>'t']), 
					['title' => Yii::t('yii', 'Edit'),]);
				},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['master/fk-delete','id' => $model->fk_id]),['title' => Yii::t('yii', 'Hapus'),'onClick'=>"return confirm('Hapus data Ini?')"]
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
            'heading'=>'<i class="fa fa-navicon"></i> Fakultas',
        ]
    ]); Pjax::end(); ?>

</div>
