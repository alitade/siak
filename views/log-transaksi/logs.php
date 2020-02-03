<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Funct;
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    #'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'label'=>'Waktu Akes',
            'value'=>function($model){
                $tgl=explode(" ",$model->tgl);
                return Funct::TANGGAL($tgl[0],2).", ".substr($tgl[1],0,8);
            },
        ],
        [
            'label'=>'User',
            'value'=>function($model){return (@$model->usr->name?:"-");},
        ],
        [
            'label'=>'Aktifitass',
            'value'=>function($model){return (@$model->aktifitas?:"-");},
        ],
    ],
    'responsive'=>false,
    'hover'=>true,
    'condensed'=>true,
    'floatHeader'=>false,
    'panel' => [
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
        'type'=>'info',
        'before'=>false,
        'after'=>Html::a('<i class="fa fa-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
        'showFooter'=>false
    ],

]);
?>

