<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Daftar Pengajar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bobot-nilai-index">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['akademik/ajr-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-pengajar'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'kr_kode',
				'label'=>'Tahun Akademik',
				'value'=>function($model){return @$model->kln->kr_kode;},
				'width'=>'10%'
			],
			[
				'attribute'=>'jr_id',
				'label'=>'Jurusan',
				'value'=>function($model){
					return @Funct::JURUSAN()[$model->kln->jr_id];
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
				
			
			],
			[
				'attribute'=>'pr_kode',
				'label'=>'Program',
				'value'=>function($model){
					return @Funct::PROGRAM()[$model->kln->pr_kode];
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Program'],
				
			],
			
			[
				'attribute'=>'mtk_kode',
				'value'=>function($model){
					return @Funct::MTK()[@$model->mtk_kode];
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::MTK(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
			],


			[
				'attribute'=>'ds_nidn',
				'value'=>function($model){
					return $model->ds->ds_nm;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(1,'ds_id'), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
			],
			[
				'attribute'=>'tot',
				'label'=>'Jadwal',
				'format'=>'raw',
/*
				'value'=>function($model){
					return Html::a($model->tot,
					Yii::$app->urlManager->createUrl(['bisa/ajr-jdw',
					'id'=> $model->id,
					'edit'=>'t'])
					,['title' => Yii::t('yii', 'Bobot'),]);
				},
*/
			],			
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{update} {delete} {view}',
                'buttons' => [
                'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
					Yii::$app->urlManager->createUrl(['akademik/ajr-update','id' => $model->id,'edit'=>'t'])
					,['title' => Yii::t('yii', 'Edit'),]);},
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-plus"></span>',
						Yii::$app->urlManager->createUrl(
							['akademik/ajr-view','id' => $model->id,'view'=>'t']),
							['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['akademik/ajr-delete','id' => $model->id,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
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
        'heading'=>'<i class="fa fa-navicon"></i> Daftar Pengajar',
    ]
    ]); Pjax::end(); ?>

</div>
