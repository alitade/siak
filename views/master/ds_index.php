<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\DosenSearch $searchModel
 */

$this->title = 'Daftar Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['ds-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-dosen'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'ds_nidn',
            [
                'attribute'=>'id_tipe',
                'label'=>'Tipe',
                'value'=>function($model){return '<span class="text-nowrap">'.$model->tipe->tipe.'</span>';},
                'format'=>'raw',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>app\models\Funct::TDS(),
                'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true],],
                'filterInputOptions'=>['placeholder'=>'Tipe'],



            ],
            'ds_nm',
//            'ds_kat', 
            'ds_email:email', 
//            'RStat', 

            [
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
					Yii::$app->urlManager->createUrl(['master/ds-update','id' => $model->ds_id,'edit'=>'t'])
					,['title' => Yii::t('yii', 'Edit'),]);},
                'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						Yii::$app->urlManager->createUrl(
							['master/ds-view','id' => $model->ds_id,'view'=>'t']),
							['title' => Yii::t('yii', 'Detail'),]
						);
					},
                'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            Yii::$app->urlManager->createUrl(
                                ['master/ds-delete','id' => $model->ds_id,'delete'=>'t']),['title' => Yii::t('yii', 'Hapus'),]
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
            'heading'=>'<i class="fa fa-navicon"></i> Daftar Dosen',
        ]
    ]); Pjax::end(); ?>

</div>
