<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\Gedungearch $searchModel
 */

$this->title = 'Daftar Gedung';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gedung-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name',
            'Lantai',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'template'=>'{view} {update} {delete}',
                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-eye"></span>',
                            Yii::$app->urlManager->createUrl(['gedung/view','id'=>$model->Id]), [
                                'title' => Yii::t('yii', 'Detail'),
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="fa fa-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['gedung/view','id' => $model->Id,'edit'=>'t']), [
                                'title' => Yii::t('yii', 'Edit'),
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="fa fa-trash"></span>',
                            Yii::$app->urlManager->createUrl(['gedung/delete','id' => $model->Id]), [
                                'title' => Yii::t('yii', 'Edit'),
                                'data-confirm'=>"Hapus Data Ini?",'data-methos'=>'post'
                            ]);
                    },

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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
