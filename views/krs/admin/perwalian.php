<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;

$this->title = 'Mahasiswa '.($KR?" (Perwalian ".$KR['nm'].") ":"");
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('perwalian_search', ['model' =>$searchModel,'kr'=>($KR?$KR['kd']:0)]);
Modal::end();
?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

$btn = Html::a('<i class="fa fa-search"></i> Pencarian Data', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]);
$nCari=0;
$ketCari='';

if(@$searchModel->jr_id){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program Studi: '.$searchModel->jr->jr_jenjang.' '.$searchModel->jr->jr_nama.'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->pr_kode){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program Perkuliahan: '.$searchModel->pr->pr_nama.'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_nim){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> NPM: --'.$searchModel->mhs_nim.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->Nama){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> Nama: --'.$searchModel->Nama.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_angkatan){$ketCari.=' <span class="label label-info"style="font-size:14px;"><i style="color:#000">Angkatan:'.$searchModel->mhs_angkatan.'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->thn){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000"> Kurikulum: '.$searchModel->thn.'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->reg){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000">Registrasi : '.($searchModel->reg?'Y':'N').'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->krs){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000">KRS: '.($searchModel->reg?'Y':'N').'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->krsHeadApp){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000">APPROVE : '.($searchModel->krsHeadApp?'Y':'S').'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->tipe){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000">Ket.: '.($searchModel->dft->keterangan?:"SMA/SMK/SEDERAJAT").'</i></span>&nbsp;'; $nCari+=1;}


if($nCari>0){
    $btn .=" ".Html::a('<i class="fa fa-refresh"></i> Reset Pencarian',['admin'],['class'=>'btn btn-info']);
    echo $ketCari='<p></p><div>'.$ketCari."&nbsp;"."</div><p></p>";
}

?>


<div class="mahasiswa-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar'=> [
            [
                'content'=>
                #Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to().'&c=1',['class'=>'btn btn-info','target'=>'_blank'])
            ],
            '{toggleData}',
            (!Funct::acc('gridview')?false:"{export}"),
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'	=> 'jr_id',
                'value'		=> function($model){
                    return '<span style="font-size: 14px;font-weight: bold">'.$model->kr_kode." - ". @$model->jr->jr_jenjang." ".@$model->jr->jr_nama." (".$model->pr->pr_nama.")</span>";
                },
                'group'     =>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'format'=>'raw',
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'label'=>'NIM | Nama',
                'attribute' => 'mhs_nim',
                'format'=>'raw',
                'value'=>function($model){
                    return " <b>".$model->mhs_nim."</b> | ".$model->Nama;
                }
            ],
            [
                'label'=>'Angkatan (Kurikulum)',
                'attribute' => 'mhs_angkatan',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_angkatan." (".$model->thn.")";
                }

            ],
            #/*
            [
                'label'=>'Ket.',
                'attribute'=>'kr.kode',
                'value'=>function($model){return ($model->dft->keterangan?:"SMA/SMK/SEDERAJAT");},
                'width'=>'20%',
            ],
            #*/
            [
                'label'=>'Reg.',
                'attribute' => 'thn',
                'width'=>'1%',
                'format'=>'raw',
                'value'=> function($model){
                    return ($model->reg==1?'<i class="glyphicon glyphicon-ok" style="color:green;"></i></i>':'<i class="glyphicon glyphicon-remove" style="color:red;"></i>');
                },
                'filter'=>['N','Y'],
            ],
            [
                'label'=>'KRS.',
                'width'=>'1%',
                'attribute' => 'krs',
                'format'=>'raw',
                'value'=> function($model){


                    $link='<span class="badge" style="background:red;"><i class="fa fa-remove" style="color:#fff;"></i></span>';
                    if($model->krs==1){$link='<span class="badge" style="background:green;"><i class="fa fa-check" style="color:#fff;"></i></span>';}
                    return Html::a($link,['/krs/admin-jadwal','id'=>$model->mhs_nim],['target'=>'_blank']);
                    #return ($model->krs==1?'<i class="glyphicon glyphicon-ok" style="color:green;"></i></i>':'<i class="glyphicon glyphicon-remove" style="color:red;"></i>');
                },
            ],
            [
                'label'=>'APP.',
                'width'=>'1%',
                'attribute' => 'krsHeadApp',
                'format'=>'raw',
                'value'=> function($model){
                    #return $model->krsHeadApp;
                    $i=($model->krsHeadApp==1?'<span class="badge" style="background:green;"><i class="fa fa-check" style="color:#fff;"></i></span>':'<span class="badge" style="background:red;"><i class="glyphicon glyphicon-remove"  style="color:fff;"></i></span>');
                    $link='<i class="fa fa-minus"></i>';
                    if($model->krs_head){$link=Html::a($i,['/krs/admin-mhs','id'=>$model->krs_head],['target'=>'_blank']);}
                    return $link;
                },
            ],
            /*
            [
                'class'=>'kartik\grid\ActionColumn',
                'template'=>'<li>{jdwl}{krs}</li>',
                'buttons' => [
                    'jdwl'=> function ($url, $model, $key)use($KR) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> Jadwal',['mhs-jadwal','nim' => $model->mhs_nim,'kr'=>$KR['kd']],['target'=>'_blank']);
                    },
                    'krs'=> function ($url, $model, $key)use($KR) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> KRS',['mhs-krs','nim' => $model->mhs_nim,'kr'=>$KR['kd']],['target'=>'_blank']);
                    },

                ],
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],
            #*/
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'bordered'=>true,
        'panel'=>[
            'before'=>$btn,
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<span style="font-size:14px;font"><i><i class="fa fa-users"> </i> '.$this->title.'</span></i>',
            'after'=>false,
            #'footer'=>false,
        ]
    ]);
    ?>
</div>
