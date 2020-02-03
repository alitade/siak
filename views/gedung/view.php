<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;




/**
 * @var yii\web\View $this
 * @var app\models\Gedung $model
 */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Gedung', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gedung-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?php Pjax::begin(); echo GridView::widget( [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rg_kode',
            'rg_nama',
            'kapasitas',
			[
				'attribute'=>'IdGedung',
				'value'=>function($model){
						return $model->gedung->Name;
					},
				'filter'=> Funct::GEDUNG($id)

			],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['ruang/view','id' => $model->IdGedung,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
