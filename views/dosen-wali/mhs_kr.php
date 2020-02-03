<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;

$this->title = 'Detail Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum Matakuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>['tabindex' => false],
    'id'=>'modals',
    'header'=>'Filter Mahasiswa',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']

]);
echo $this->render('mhs__search', ['model' => $searchModel,'subAkses'=>$subAkses]);
Modal::end();

Modal::begin([
    'options'=>['tabindex' => false],
    'id'=>'modals1',
    'header'=>'Detail Matakuliah',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']

]);

echo GridView::widget([
    'dataProvider' => $dataMk,
    #'filterModel' => $searchMk,
    'columns' => [
        [
            'attribute'	=> 'semester',
            'width'=>'20%',
            'value'=>function($model){ return "Semester ".$model->semester;},
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
        ],
        ['class' => 'kartik\grid\SerialColumn',],
        #['attribute'=>'kode','width'=>'10%','pageSummary'=>'Total SKS',],
        [
            'attribute'=>'matkul',
            'value'=>function($model){
                return $model->kode.': '.$model->matkul;

            },
            'pageSummary'=>'Total SKS',
        ],
        ['attribute'=>'sks','pageSummary'=>true,'width'=>'1%'],
        ['label'=>'Prasyarat','attribute'=>'kode_',],
    ],
    'showPageSummary' => true,
    'responsive' => false,
    'hover' => true,
    'condensed' => true,
    'floatHeader' => false,
    'panel' => [
        'header'=>true,
        'type' => 'info',
        'before' =>false,
        'footer'=>false,
    ],
]);

Modal::end();


?>
<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">Kurikulum <?= Funct::JURUSAN()[$model->jr_id]." [ $model->kode | ".($model->aktif==1?"Aktif":(!Funct::acc('/matkul-kr/aktif')?"":Html::a("Aktifkan",['aktif','id'=>$model->id],['class'=>'badge','style'=>'color:#fff;font-weight:bold'])))." ]"?></span>
    <div class="pull-right">
        <?= " &sum;SKS ".($model->totSks?:0) ?>
    </div>
    <div style="clear: both"></div>
</div>
<p></p>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'toolbar'=> [
        ['content'=>false],
        '{toggleData}',
        (!Funct::acc('gridview')?false:"{export}"),
    ],
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'width'=>'1%',
        ],
        [
            'attribute'	=> 'pr_kode',
            'value'		=> @function($model){return @$model->pr->pr_nama;},
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
    			],
                [
                    'attribute' => 'mhs_nim',
                    'value'=>function($model){
                        return $model->mhs_nim;
                    }
                ],
                [
                    'attribute' => 'Nama',
                    'label'=>'Nama',
                    'value'=>function($model){
                        return $model->Nama;
                    }
                ],
                [
                    'label'=>'Angkatan',
                    'attribute'=>'thn',

                    'value'=>function($model){
                        return $model->mhs_angkatan.' ('.$model->thn.')';
                    }

                ],
                [
                    'attribute'	=> 'ws',
                    'width'=>'5%',
                    'format'=>'raw',
                    'value'		=> function($model){
                        return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
                    },
                    'filter'=>['N','Y'],

                ],
            ],
            'responsive'=>false,
            'hover'=>true,
            'condensed'=>true,
            'panel'=>[
    'type'=>GridView::TYPE_PRIMARY,
    'heading'=>'<i class="fa fa-users"></i> Daftar Mahasiswa',
    'header'=>true,
    'before'=>
        Html::a('<i class="fa fa-search"></i> Filter Data Mahasiswa', ['#'], ['class' => 'btn btn-success','id'=>'popupModal' ])." "
        .Html::a('<i class="fa fa-eye"></i> Matakuliah', ['#'], ['class' => 'btn btn-success','id'=>'popupModal1' ])." "
        .(!Funct::acc('/matkul-kr/delete')?" ":Html::a("<i class='fa fa-plus'></i> Tambah Data Mahasiswa",['mhs-create','id'=>$model->id],["class"=>'btn btn-primary','name'=>'u']))." "
        ,
    ]
        ]); ?>


<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

$this->registerJs("$(function() {
   $('#popupModal1').click(function(e) {
     e.preventDefault();
     $('#modals1').modal('show').find('.modal-content').html(data);
   });
});");


?>
