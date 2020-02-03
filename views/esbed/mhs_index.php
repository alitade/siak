<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\MahasiswaSearch $searchModel
 */

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mahasiswa-index">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['mhs-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
			],
            [
                'attribute' => 'Nama',
                'width'=>'20%',
            ],
            'mhs_nim',
			
            'mhs_angkatan',
			[
				'label'=>'Kurikulum',
				'attribute'=>'thn',
                'width'=>'20%',
			],

//            'mhs_stat', 
			[
				'attribute'	=> 'ws',
                'width'=>'5%',
				'format'=>'raw',
				'value'		=> function($model){
						return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
					},
				'filter'=>['N','Y'], 
				
			],
			[
				'attribute'	=> 'ds_wali',
                //'width'=>'20%',
				'value'		=> function($model){return @app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali];},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::DSN(1,'ds_id'), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'- Dosen Wali -'],
			],
/*			
			[
				'attribute'	=> 'ws',
                'width'=>'5%',
				'format'=>'raw',
				'value'		=> function($model){
						return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
					},
				'filter'=>['N','Y'], 
				
			],
*/
            [
                'class' => 'kartik\grid\ActionColumn',
				'template'=>'{view}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                        Yii::$app->urlManager->createUrl(['akademik/mhs-update','id' => $model->mhs_nim,'edit'=>'t']), 
                        ['title' => Yii::t('yii', 'Edit'),]);},
                    'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['akademik/mhs-view','id' => $model->mhs_nim,'view'=>'t']),['title' => Yii::t('yii', 'View'),]
                            );
                        },
                    'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['akademik/mhs-delete','id' => $model->mhs_nim,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
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
            'heading'=>'<i class="fa fa-navicon"></i> Mahasiswa',
        ]
    ]); Pjax::end(); ?>

</div>
