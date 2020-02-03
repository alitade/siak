<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\TAbsenDosenSearch $searchModel
 */

$this->title = 'Perkuliahan Hari Ini';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tabsen-dosen-index">
    <?php
    #Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'jdwl_id',
                'label'=>'ID',
                'width'=>'2%'

            ],
            [
                'label'=>'Matakuliah',
                'attribute'=>'mtk_kode',
                'format'=>'raw',
                'value'=>function($model){
                    return '<u><b>'.$model->ds_nm.'</b></u><br>'.$model->mtk_kode.' '.$model->mtk_nama."($model->jdwl_kls)";
                },
            ],
            [
                'attribute'=>'jdwl_masuk',
                'label'=>'Jam Masuk',
                'format'=>'raw',
                'value'=>function($model){
                    return "<u><b>".substr($model->jdwl_masuk,0,5).'-'.substr($model->jdwl_keluar,0,5)."</b></u> | "
                        ."<b>".($model->ds_masuk?substr($model->ds_masuk,0,5):"__:__").'-'.($model->ds_keluar?(substr($model->ds_keluar,0,5)):"__:__")."</b><br>"
                        ."Durasi [".$model->dM."m : ".$model->dK."m] (".Funct::HARI()[$model->jdwl_hari] .")";

                },
            ],
            [
                'label'=>'Pelaksanaan',
                'format'=>'raw',
                'value'=>function($model){
                    $ket="";
                    if(!$model->ds_masuk){
                        $ket="Belum Absen Masuk!";
                    }else{if(!$model->ds_keluar){$ket="Belum Absen Keluar!";}}

                    if($model->ds_stat==1){$ket="<font color='green'>SELESAI</font>";}
                    if($model->ds_stat==2){$ket="Absen Keluar Sebelum Waktunya!";}
                    if($model->ds_stat==='0'){$ket="Kehadiran Dibatalkan!";}

                    return '<u><b>'.($model->pengajar->ds_nm?:"--------------").'</b></u><br>'
                    ." <b><font color='red'>$ket</font> </b>";
                },
            ],
            [
                'header'=>'<i class="fa fa-users"></i>/<i class="glyphicon glyphicon-ok-circle"></i>',
                'format'=>'raw',
                'mergeHeader'=>true,
                'value'=>function($model){
                    return Html::a($model->tMhs.'/'.$model->tHdr,['/perkuliahan/berjalan-v','id'=>$model->id],['target'=>'_blank']);
                }
            ],
            [
                'header'=>' ',
                'format'=>'raw',
                'mergeHeader'=>true,
                'value'=>function($model){
                    $status =($model->ds_stat==1?1:0);
                    $btn ='<i class="glyphicon glyphicon-remove-circle" style="color:red" id="_'.$model->jdwl_id.'"></i>';
                    if($status){$btn ='<i class="glyphicon glyphicon-ok-circle" style="color:green"></i>';}
                    return $btn;
                }
            ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'template'=>'
                    <li>{gds}</li>
                    <li>{fp_awal}</li><li>{pAwal}</li><li>{_m}</li>',
                'buttons' => [

                    'pAwal'=> function ($url, $model, $key) {
                        if(!Funct::acc('/perkuliahan/pulang-awal')){return false;}
                        return Html::a('<i class="fa fa-bell"></i> Pulang Awal'
                            ,['perkuliahan/pulang-awal','id' => $model->id]);
                    },
                    '_m'=> function ($url, $model, $key) {
                        if($model->ds_masuk){
                            if(!$model->ds_keluar){
                                if(!Funct::acc('/perkuliahan/keluar')){return false;}
                                return Html::a('<i class="fa fa-upload"></i> Absen Keluar'
                                    ,['perkuliahan/keluar','id' =>$model->id],['target'=>'_blank']);
                            }
                            return false;
                        }
                        if(!Funct::acc('/perkuliahan/masuk')){return false;}
                        return Html::a('<i class="fa fa-upload"></i> Masuk',['perkuliahan/masuk','id' => $model->id],['target'=>'_blank']);
                    },


                    'gds'=> function ($url, $model, $key) {
                        if(!Funct::acc('/perkuliahan/berjalan-gds')){return false;}
                        if($model->ds_get_id||$model->ds_get_fid){return false;}
                        return Html::a('<i class="fa fa-users"></i> Ganti Pengajar',['perkuliahan/berjalan-gds','id'=>$model['id']]);
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
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>false,#Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]);
    #Pjax::end();
    ?>

</div>
