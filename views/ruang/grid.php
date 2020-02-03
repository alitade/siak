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

if(!$LINK){
    $LINK=[
        'view' => function ($url, $model) {
            if(!Funct::acc('/ruang/view')){return false;}
            return Html::a('<i class="fa fa-eye"></i>',Yii::$app->urlManager->createUrl(['ruang/view','id' => $model->id,]),['title' =>'Detail','class'=>'btn btn-success btn-xs']);
        },
        'update' => function ($url, $model) {
            if(!Funct::acc('/ruang/update')){return false;}
            return Html::a('<i class="fa fa-pencil"></i>',Yii::$app->urlManager->createUrl(['ruang/index','id' =>$model->id_gedung,'rg'=>$model->id]),['title' =>'Detail','class'=>'btn btn-success btn-xs']);
        },
        'delete' => function ($url, $model) {
            if(!Funct::acc('/ruang/delete')){return false;}
            return Html::a('<i class="fa fa-trash"></i>',Yii::$app->urlManager->createUrl(['ruang/delete','id' => $model->id,]),
                ['title' =>'Detail','class'=>'btn btn-danger btn-xs','data-method'=>'post','data-confirm'=>'Hapus data ini?']);
        },

    ];

}

?>
<div class="ruang-index">
    <?php
    Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'IdGedung',
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                'format'=>'raw',
                'value'=>function($model){return "<span style='font-weight: bold;font-size:14px'>".$model->gedung->nama."</span>";},
                'visible'=>$GD?false:true,
            ],
            'rg_kode',
            'rg_nama',
            'kapasitas',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'<div class="text-nowrap">{view} {update} {delete}</div>',
                'buttons' => $LINK,
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'toolbar'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'primary',
            'before'=>false,
            'after'=>false,
            'showFooter'=>false,


        ],
    ]); Pjax::end(); ?>
</div>
