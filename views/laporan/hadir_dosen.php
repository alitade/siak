<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;

$this->title = 'Laporan Kehadiran Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header bg-aqua with-border" style="font-weight: bold;font-size:14px">*Klik <span class="btn btn-default btn-sm">Action</span> <i class="fa fa-arrow-right"></i> <span class="btn btn-default btn-sm"><i class="fa fa-eye"> </i>Detail</span> untuk melihat detail revisi</div>

</div>
<div class="labsen-dosen-index">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        #'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label'=>'Tanggal Rekap',
                'value'=>function($model){
                    $tgl=explode(" ",$model->ctgl);
                    return Funct::TANGGAL($tgl[0]).", ".substr($tgl[1],0,8);
                }
            ],
            [
                'label'=>'Kelas',
                'value'=>function($model){
                    if($model->tipe=='0'){return 'Pagi';}
                    if($model->tipe=='1'){return 'Sore';}
                    return '-';
                }
            ],

            [
                'label'=>'Kurikulum',
                'value'=>function($model){return $model->kr_kode.' - '.$model->kr->kr_nama;}
            ],
            [
                'label'=>'Periode',
                'value'=>function($model){
                    return Funct::TANGGAL($model->tgl_awal).' -  '.Funct::TANGGAL($model->tgl_akhir)    ;
                }
            ],
            [
                'label'=>'Tot. Rev.',
                'format'=>'raw',
                'value'=>function($model){
                    return count($model->total);
                    return Html::a(count($model->total),['kehadiran-dosen-detail','id'=>$model->id],['class'=>'btn btn-xs btn-primary']);
                    if($model->parent){
                        return 'Ke-'.$model->rf_count;
                    }
                    return '0';
                }
            ],
            [
                'label'=>'Operator',
                'value'=>function($model){
                    return $model->usr->name;
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>"<li>{ex}</li><li>{view}</li><li>{rev}</li> <li>{delete}</li>",
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],

                'buttons' => [
                    'ex' => function ($url, $model) {
                        if(!Funct::acc('/laporan/download-persensi-dosen')){return false;}
                        return Html::a('<i class="fa fa-download"></i> Download File', Yii::$app->urlManager->createUrl(['/laporan/download-persensi-dosen','id' => $model->id]), [
                            'title' => Yii::t('yii', 'Donload File'),'target'=>'_blank']);
                    },
                    'rev' => function ($url, $model) {
                        if(!Funct::acc('/laporan/kehadiran-dosen-rf')){return false;}
                        return Html::a('<i class="fa fa-refresh"></i> Revisi', Yii::$app->urlManager->createUrl(['/laporan/kehadiran-dosen-rf','id' => $model->id]), [
                            'title' => Yii::t('yii', 'Revisi Laporan'),'target'=>'_blank'
                        ]);
                    },
                    'view' => function ($url, $model) {
                        if(!Funct::acc('/laporan/kehadiran-dosen-detail')){return false;}

                        return Html::a('<span class="fa fa-eye"></span> Detail', Yii::$app->urlManager->createUrl(['laporan/kehadiran-dosen-detail','id'=>$model->id]), [
                            'title' => Yii::t('yii', 'Detail'),'target'=>'_blank'
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if(Funct::acc('/laporan/hadir-dosen-del')){
                            return Html::a('<span class="fa fa-trash"></span> Hapus', Yii::$app->urlManager->createUrl(['laporan/hadir-dosen-del','id'=>$model->id,]), [
                                'title' => Yii::t('yii', 'Hapus'),'data-method'=>'post','data-confirm'=>'Jika Terdapat data Revisi, Data Revisi Tersebut Akan ikut Terhapus, Lanjutkan Penghapusan data?'
                            ]);
                        }
                        return false;
                    },

                ],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="fa fa-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>
                (Funct::acc('/laporan/kehadiran-dosen-add')? Html::a('<i class="fa fa-plus"></i> Tambah Data', ['/laporan/kehadiran-dosen-add'], ['class' => 'btn btn-success']):"" ),
            'after'=>false,
            'showFooter'=>false,
            #'footer'=>false,
        ],
    ]);
    ?>

</div>
