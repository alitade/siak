<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use yii\bootstrap\Modal;
use app\models\Funct;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JurusanSearch $searchModel
 */

$this->title = 'Jurusan';
$this->params['breadcrumbs'][] = $this->title;
Modal::begin([
    'options'=>['tabindex' => false],
    'id'=>'modals',
    'header'=>'Filter Jurusan',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']
]);
echo $this->render('_search', ['model' => $searchModel]);
Modal::end();
?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
?>

<div class="jurusan-index">
    <?php
    if(@$searchModel->jr_id){
        echo '<div class=" alert alert-info" style="padding:3px">';
        if(@$searchModel->jr_id){echo '<span class="badge">ID Jurusan [ '.$searchModel->jr_id.'] </span> ';}
        echo'</div>';

    }
    echo Html::a('<i class="fa fa-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]).' '
        .(Funct::acc('/jurusan/create')? Html::a('<i class="fa fa-plus"></i> Tambah', ['jr-create'],['class'=>'btn btn-success']).' ':"")
        .Html::a('<i class="fa fa-download"></i> Export', ['report-jurusan'],['class'=>'btn btn-info']).' '
        ."<p> </p>";
    echo $this->render('grid', ['dataProvider' => $dataProvider,'searchModel' => $searchModel,]);

//    echo GridView::widget([
//        'dataProvider' => $dataProvider,
//#        'filterModel' => $searchModel,
//        'toolbar'=> [
//            ['content'=>
//                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['jr-create'],['class'=>'btn btn-success']).''.
//                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-jurusan'],['class'=>'btn btn-info'])
//            ],
//            '{toggleData}',
//        ],
//        'columns' => [
//            ['class' => 'kartik\grid\SerialColumn'],
//            [
//
//                'label'=>'Fakultas',
//                'format'=>'raw',
//                'group'=>true,  // enable grouping,
//                'groupedRow'=>true,                    // move grouped column to a single grouped row
//                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
//                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
//                'value'=>function($model){return "<span style='font-size: 14px;font-weight: bold'>".$model->fk->fk_nama."</span>";},
//                'visible'=>$FK?false:true,
//
//            ],
//            [
//                'label'=>'Kode',
//                'value'=>function($model){return $model->jr_id;}
//            ],
//            [
//                'label'=>'Jurusan',
//                'value'=>function($model){return $model->jr_jenjang.' '.$model->jr_nama;}
//            ],
//            [
//                'attribute'=>'jr_head',
//                'filter'=>false,
//            ],
//            [
//                'class' => 'kartik\grid\ActionColumn',
//                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
//                            Yii::$app->urlManager->createUrl(
//                                ['master/jr-view','id' => $model->jr_id,'view'=>'t']),['title' => Yii::t('yii', 'Detail'),]
//                        );
//                    },
//                    'update' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
//                            Yii::$app->urlManager->createUrl(
//                                ['master/jr-update','id' => $model->jr_id,'edit'=>'t']),['title' => Yii::t('yii', 'Edit'),]
//                        );
//                    },
//                    'delete' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
//                            Yii::$app->urlManager->createUrl(
//                                ['/master/jr-delete','id' => $model->jr_id]),[
//                                'title' => Yii::t('yii', 'Hapus'),
//                                'onClick'=>"return confirm('Hapus data Ini?')",
//                            ]
//                        );
//                    },
//                ],
//            ],
//        ],
//        'responsive'=>true,
//        'hover'=>true,
//        'condensed'=>true,
//        'panel'=>[
//            'before'=>Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
//            'type'=>GridView::TYPE_PRIMARY,
//            'heading'=>'<i class="fa fa-navicon"></i> Jurusan',
//        ]
//    ]);
    ?>

</div>
