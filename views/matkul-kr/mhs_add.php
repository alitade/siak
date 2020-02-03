<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;

$this->title = 'Update Kurikulum Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Detail', 'url' => ['mhs','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'id'=>'modals',
    'header'=>'Filter Mahasiswa',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']

]);
echo $this->render('mhs__search', ['model' => $searchModel,'subAkses'=>$subAkses,'ID'=>$model->id]);
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
        <?=
        "&sum; <i class='fa fa-users'></i> ".($totMhs['mhs']?:0)." | &sum;SKS ".($model->totSks?:0).""
        ?>
    </div>
    <div style="clear: both"></div>
</div>
<p></p>

<?php
$btn = Html::a('<i class="fa fa-search"></i> Pencarian Data Mahasiswa', ['#'], ['class' => 'btn btn-info','id'=>'popupModal' ]);
$nCari=0;
$ketCari='';
if(@$searchModel->pr_kode){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program: '.$searchModel->pr->pr_nama.'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_nim){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> NPM: --'.$searchModel->mhs_nim.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->Nama){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> Nama: --'.$searchModel->Nama.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_angkatan){$ketCari.=' <span class="label label-info"style="font-size:14px;"><i style="color:#000">Angkatan:'.$searchModel->mhs_angkatan.'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->thn){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000"> Kurikulum: '.$searchModel->thn.'</i></span>&nbsp;'; $nCari+=1;}
if($nCari>0){
    $btn .=" ".Html::a('<i class="fa fa-refresh"></i> Reset Pencarian',['mhs-create','id'=>$model->id],['class'=>'btn btn-info']);
    echo $ketCari='<p></p><div>'.$ketCari."&nbsp;"."</div><p></p>";
}

$form1 = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig'=>[
        'labelSpan'=>2,'options'=>['onSubmit'=>'return confirm("Hapus Data?")']]
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'toolbar'=> [
        ['content'=>false],
        '{toggleData}',
        (!Funct::acc('gridview')?false:"{export}"),
    ],
    'columns' => [
        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>[
                'class'=>'kartik-sheet-style'
            ],
            'visible'=>Funct::acc('/matkul-kr/delete'),
        ],
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
                    'attribute'=>'mk_kr',
                    'label'=>'Kurikulum Mk.',
                    'value'=>function($model){
                        return $model->kr->kode;
                    }

                ],
                [
                    'attribute'	=> 'ws',
                    'width'     => '5%',
                    'format'    => 'raw',
                    'value' 	=> function($model){
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
    'heading'=>'<i class="fa fa-users"></i>  Update Kurikulum Mahasiswa',
    'header'=>true,
    'before'=>
        #Html::a('<i class="fa fa-search"></i> Filter Data Mahasiswa', ['#'], ['class' => 'btn btn-success','id'=>'popupModal' ])." "
        $btn.' '
        .Html::a('<i class="fa fa-eye"></i> Matakuliah', ['#'], ['class' => 'btn btn-info','id'=>'popupModal1' ])." "
        .(!Funct::acc('/matkul-kr/delete')?" ":Html::submitButton("<i class='fa fa-pencil'></i> Update Kurikulum Mahasiswa",["class"=>'btn btn-primary','name'=>'u']))." "
    ,
]
        ]);
        ActiveForm::end();
        ?>


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
