<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;

$this->title = 'Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('mhs__search', ['model' => $searchModel]);
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
    $btn .=" ".Html::a('<i class="fa fa-refresh"></i> Reset Pencarian',['dosen'],['class'=>'btn btn-info']);
    echo $ketCari='<p></p><div>'.$ketCari."&nbsp;"."</div><p></p>";
}


?>


<p> </p>
<div class="mahasiswa-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
#        'filterModel' => $searchModel,
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
                'value'		=> function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
                'group'     =>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute'	=> 'pr_kode',
                'value'		=> function($model){return @$model->pr->pr_nama;},
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
			],
            [
                'label'=>'NIM | Nama',
                'attribute' => 'Nama',
                'format'=>'raw',
                'value'=>function($model){
                    return " <b>".$model->mhs_nim."</b> | ".$model->Nama;
                }
            ],
            /*
            [
                'attribute' => 'mhs_nim',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_nim;
                    return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
                }
            ],
            */
            [
                'label'=>'Angkatan (Kurikulum)',
                'attribute' => 'mhs_angkatan',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_angkatan." (".$model->thn.")";
                }

            ],
			[
                'label'=>'Matakuliah',
                'attribute'=>'kr.kode',
                'width'=>'20%',
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

			[
                'class'=>'kartik\grid\ActionColumn',
                'template'=>
                    '
								<li>{view}</li>
								<li>{export}</li>
								<li>{konversi}</li>
								<li>{transkrip}</li>
								<li>{CtkTrans}</li>
								<li>{urgent}</li>
					'
                ,
                'buttons' => [
                    'view'=> function ($url, $model, $key) {
                        if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail',['/mhs/view','id' => $model->mhs_nim]);
                    },
                    'CtkTrans'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/cetak/cetak')){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-print"></span> Cetak Transkrip Sementara',
                            ['/transkrip/cetak/cetak','id' => $model->mhs_nim,]
                        );
                    },
                    'export'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/pindah')){return false;}
                        if($model->ws){return false;}
                        return Html::a('<i class="glyphicon glyphicon-list"></i> Export Nilai'
                            ,['/transkrip/nilai/pindah','id' => $model->mhs_nim,'view'=>'t']);
                    },
                    'konversi'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/konversi')){return false;}
                        if($model->ws){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span> Konversi',
                            ['/transkrip/nilai/konversi','id' => $model->mhs_nim,]
                        );
                    },
                    'transkrip'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/wisuda/transkrip')){return false;}
                        if(!$model->wisuda->id){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span> Transkrip',
                            ['/transkrip/wisuda/transkrip','id' => $model->wisuda->id,]
                        );
                    },
                    'urgent'=> function ($url, $model, $key) {
                        if(!Funct::acc('/transkrip/nilai/nilai-urgent')){return false;}
                        if($model->ws){return false;}
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span>Nilai Prodi',
                            ['/transkrip/nilai/nilai-urgent','id' => $model->mhs_nim,]
                        );
                    },

                ],
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        'before'=>$btn,
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> '.$this->title,
    ]
    ]); ?>

</div>
