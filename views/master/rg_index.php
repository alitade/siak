<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\Ruangearch $searchModel
 */

$this->title = 'Ruangan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-index">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['rg-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-ruangan'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'rg_kode',
            'rg_nama',
            'kapasitas',
            'Qujian',
			[
				'attribute'=>'IdGedung',
				'value'=>function($model){
						return @$model->gedung->Name;
					},
				'filter'=> Funct::GEDUNG()
			
			],
            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
					Yii::$app->urlManager->createUrl(['master/rg-update','id' => $model->rg_kode,'edit'=>'t']),
					['title' => Yii::t('yii', 'Edit'),]);},
					
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['master/rg-view','id' => $model->rg_kode,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>',
						Yii::$app->urlManager->createUrl(
							['master/rg-delete','id' => $model->rg_kode,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),'onClick'=>"return confirm('Hapus Data Ini?')"]
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
            'heading'=>'<i class="fa fa-navicon"></i> Ruangan',
        ]
    ]); Pjax::end(); ?>

</div>
