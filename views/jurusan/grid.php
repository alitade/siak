<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;


$link=[
    'view' => function ($url, $model) {
        if(!Funct::acc('/jurusan/view')){return false;}
        return Html::a('<i class="fa fa-eye"></i>', Yii::$app->urlManager->createUrl(['jurusan/view','id' => $model->id,]),
            ['title' => Yii::t('yii', 'Detail'),'class'=>'btn btn-success btn-xs']);
    },
    'update' => function ($url, $model) {
        if(!Funct::acc('/jurusan/update')){return false;}
        return Html::a('<i class="fa fa-pencil"></i>', Yii::$app->urlManager->createUrl(['jurusan/update','id' => $model->id,]),
            ['title' => Yii::t('yii', 'Edit'),'class'=>'btn btn-success btn-xs']);
    },
    'delete' => function ($url, $model) {
        if(!Funct::acc('/jurusan/delete')){return false;}
        return Html::a('<i class="fa fa-trash"></i>', Yii::$app->urlManager->createUrl(['jurusan/delete','id'=>$model->id,]),
            ['title' => Yii::t('yii', 'Hapus Data'),'data-method'=>'post','data-confirm'=>'Hapus Data Ini?','class'=>'btn btn-danger btn-xs']);
    },

];
if(!$LINK){$LINK=$link;}

?>

<?php Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [

            'label'=>'Fakultas',
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            'format'=>'raw',
            'value'=>function($model){return "<span style='font-weight: bold;font-size:14px'>".$model->fk->fk_nama."</span>";},
            'visible'=>$FK?false:true,
        ],
        [
            'label'=>'Kode',
            'value'=>function($model){return $model->jr_id;}
        ],
        [
            'label'=>'Jurusan',
            'value'=>function($model){
                return $model->jr_jenjang.' '.$model->jr_nama.($model->jr_name?" ( $model->jr_name )":"");

            }
        ],
        [
            'label'=>'Kajur.',
            'value'=>function($model){return $model->ds_id;}
        ],
        [
            'label'=>'Program',
            'value'=>function($model){return count($model->program);}
        ],
        [
            'label'=>'Konsentrasi',
            'value'=>function($model){
                return count($model->konsentrasi);
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template'=>'<div class="text-nowrap">{view} {update} {delete}</div>',

            'buttons' => $LINK,
        ],
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    'floatHeader'=>false,
    'toolbar'=>false,
    'panel' => [
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode( $TITLE?:$this->title).' </h3>',
        'type'=>'primary',
        'before'=>false,
        'after'=>false,
        'showFooter'=>false
    ],
]); Pjax::end(); ?>

