<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\KonsultanSearch $searchModel
 */

$this->title = 'Konsultan';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
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
<div class="konsultan-index">
    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kode',
            'vendor',
            'email:email',
            'tlp',
            'pic',
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'template'=>'<li>{view}</li><li>{update}</li><li>{program}</li><li>{tarif}</li><li>{mahasiswa}</li>',
                'buttons' => [
                    'view'=>function($url, $model, $key) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-eye"></i> Detail',['view','id' => $model->kode]);
                    },
                    'update'=>function($url, $model, $key) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-pencil"></i> Update',['update','id' => $model->kode]);
                    },
                    'program'=>function($url, $model, $key) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> Program',['update','id' => $model->kode]);
                    },
                    'tarif'=>function($url, $model, $key) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> Tarif',['update','id' => $model->kode]);
                    },
                    'mahasiswa'=>function($url, $model, $key) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-users"></i> Mahasiswa',['mahasiswa','id' => $model->kode]);
                    },

                ]
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>
                Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]).' '.
                Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
