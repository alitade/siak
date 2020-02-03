<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = $model->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['dsn']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-view">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>true,
    'hover'=>true,
    'enableEditMode'=>false,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>$this->title,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
            'ds_nidn',
            'ds_user',
            'ds_nm',
            'ds_kat',
            'ds_email:email',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->ds_nidn],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
				'filterInputOptions'=>['placeholder'=>'-Progaram-'],
			],
            [
                'attribute' => 'mhs.people.Nama',
                'width'=>'20%',
            ],
            'mhs_nim',
            'mhs_angkatan',
//            'mhs_stat', 
            [
                'class' => 'kartik\grid\ActionColumn',
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
            'heading'=>'<i class="fa fa-navicon"></i> List Mahasiswa',
			//'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['akademik/dsn-wali','id'=>$model->ds_id], ['class' => 'btn btn-info']),
        ]
    ]); Pjax::end(); ?>


</div>
