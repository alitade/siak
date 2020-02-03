<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title = 'Dosen Wali';
$this->params['breadcrumbs'][] = $this->title;

#add dosen wali
Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'<i class="fa fa-plus"></i> Tambah Dosen Wali',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modalsAdd',
    'clientOptions'=>['show'=>($model->getErrors()?true:false),],

]);
echo $this->render('_form', ['model' => $model,'subAkses'=>$subAkses,
]);
Modal::end();


#Filter
Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'Filter Data Dosen Wali',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('_search', ['model' => $searchModel]);
Modal::end();

$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

?>

    <div class="dosen-wali-index">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php Pjax::begin();
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute'=>'jr_id',
                    'value'=>function($model){
                        return $model->jr->jr_jenjang." ".$model->jr->jr_nama;
                    },
                ],
                [
                    'attribute'=>'dosen',#'value'=>function($model){return $model->ds->ds_nm;},
                ],
                [
                    'header'=>'&sum;<i class="fa fa-users"></i>',
                    'value'=>function($model){return count($model->mhs);},
                ],
                [
                    'attribute'=>'aktif',
                    'value'=>function($model){return $model->aktif;},
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template'=>'<li>{view}</li><li>{mhs}</li><li>{aktif}</li><li>{delete}</li>'
                    ,
                    'dropdown'=>true,
                    'dropdownOptions'=>['class'=>'pull-right'],
                    'headerOptions'=>['class'=>'kartik-sheet-style'],
                    'buttons' => [
                        'mhs'=> function ($url, $model, $key) {
                            if(!\app\models\Funct::acc('/dosen-wali/mhs-create')){return false;}
                            return Html::a('<span class="fa fa-plus"></span> Tambah Mahasiswa',['mhs-create','id' => $model->jr_id,'id1'=>$model->ds_id,]);
                        },
                        'delete'=> function ($url, $model, $key) {
                            if(!\app\models\Funct::acc('/dosen-wali/delete')){return false;}
                            return Html::a('<span class="fa fa-trash"></span> Delete',['delete','id' => $model->jr_id,'id1'=>$model->ds_id,],['data-method'=>'post','data-confirm'=>'Hapus Data Ini?']);
                        },
                        'view'=> function ($url, $model, $key) {
                            if(!\app\models\Funct::acc('/dosen-wali/view')){return false;}
                            return Html::a('<span class="fa fa-eye"></span> Detail',['view','id' => $model->jr_id,'id1'=>$model->ds_id,]);
                        },
                        'aktif'=> function ($url, $model, $key) {
                            if(!\app\models\Funct::acc('/dosen-wali/aktif')){return false;}
                            return Html::a('<span class="fa fa-check"></span> Aktif',['/dosen-wali/aktif','id' => $model->jr_id,'id1'=>$model->ds_id]);
                            if($model->aktif==0){return Html::a('<span class="fa fa-remove"></span> Aktif',['/dosen-wali/aktif','id' => $model->jr_id,'id1'=>$model->ds_id]);}
                            #return false;

                        },

                    ],
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
                    (\app\models\Funct::acc('/dosen-wali/create')?Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success','id'=>'popupModalAdd']):"")
                    ." ".Html::a('<i class="fa fa-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
                'after'=>Html::a('<i class="fa fa-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
                'showFooter'=>false
            ],
        ]);
        Pjax::end();
        ?>

    </div>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

$this->registerJs("$(function() {
   $('#popupModalAdd').click(function(e) {
     e.preventDefault();
     $('#modalsAdd').modal('show').find('.modal-content').html(data);
   });
});");