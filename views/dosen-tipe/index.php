<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use  app\models\Funct;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\DosenTipeSearch $searchModel
 */

$this->title = 'Kategori Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-tipe-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'tipe',
            [
                'label'=>'Maks. SKS',
                'attribute'=>'maxsks',
                'value'=>function($model){
                    return $model->maxsks;
                }

            ],
            [
                'label'=>'Dosen',
                'value'=>function($model){
                    return count($model->dosen);
                }

            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'template'=>'
                    <li>{view}</li>
                    <li>{update}</li>
                    <li>{delete}</li>
                ',

                'buttons' => [

                    'update'=> function ($url, $model, $key) {
                        if(!Funct::acc('/dosen-tipe/update')){return false;}
                        return Html::a('<span class="fa fa-pencil-square-o"></span> Update',['update','id' => $model->id,]);
                    },

                    'view'=> function ($url,$model,$key) {
                        if(!Funct::acc('/dosen-tipe/delete')){return false;}
                        return Html::a('<span class="fa fa-eye"></span> Detail',['view','id'=>$model->id,]);
                    },

                    'delete'=> function ($url, $model, $key) {
                        if(!Funct::acc('/dosen-tipe/delete')){return false;}
                        return Html::a('<span class="fa fa-trash"></span> Delete',['delete','id' => $model->id,]);
                    },

                    /*'sks'=> function ($url, $model, $key) {
                        if(!Funct::acc('/dosen-tipe/sks')){return false;}
                        return Html::a(
                            '<span class="fa fa-plus"></span>Beban SKS',
                            ['dosen/absensi','id' => $model->id,]
                        );
                    },*/
                ],
            ],
        ],
        'responsive'=>false,
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
