<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;
use \app\models\Funct;

$this->title = 'Master Perkuliahan ';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="page-header"><h3><?= Html::encode($this->title) ?></h3></div>

    <div class="angge-search">
        <?php $form = ActiveForm::begin([
            'action' => ['master-absen-dosen'],
            'method' => 'get',
        ]); ?>
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <?=
                $form->field($searchModel,'kr_kode_')->widget(Select2::classname(), [
                    'data' =>app\models\Funct::AKADEMIK(),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
                <?php // echo $form->field($model, 'Tipe') ?>

                <div class="form-group" style="text-align: right;">
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dirit/jdw'],['class' => 'btn btn-danger']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <br /><br />
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'jdwl_id'
            ],
            [
                'label'=>'Program',
                'attribute'=>'pr_nama',
                //'value'=>function($model){return 'Program';},
            ],
            [
                'attribute'=>'sesi',
                'label'=>'Sesi',
                //'value'=>function($model){return '1';},
            ],
            [
                'attribute'=>'jdwl_hari',
                'label'=>'Jadwal',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\Funct::HARI()[$model->jdwl_hari]."<br />".substr($model->jdwl_masuk,0,5).'-'.substr($model->jdwl_keluar,0,5);
                    #return $model->jAwal;
                },
                'filter'=>@app\models\Funct::HARI(),
                'contentOptions'=>['class'=>'col-xs-1',],
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
                'label'=>'Pelaksanaan',
                //'attribute'=>'pelaksana',
                'format'=>'raw',
                'value'=>function($model){
                    return '<u><b>'.$model->pelaksana.'</b></u><br>'
                        .($model->tgl_perkuliahan?\app\models\Funct::TANGGAL($model->tgl_perkuliahan):"").substr($model->ds_masuk,0,5).'-'.substr($model->ds_keluar,0,5);
                },
            ],
            [
                'header'=>'<i class="fa fa-users"></i>|<i class="glyphicon glyphicon-ok-circle"></i>',
                'format'=>'raw',
                'mergeHeader'=>true,
                'value'=>function($model){
                    return $model->tMhs.'|'.$model->totHdr;
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
                'template'=>'<li>{edit}</li><li>{Fganti}</li><li>{ganti}</li>',
                'buttons' => [
                    'edit'=> function ($url, $model, $key) {
                        #if(!Funct::acc('/perkuliahan/detail-perkuliahan')){return false;}
                        return Html::a('<i class="fa fa-pencil"></i> Update Kehadiran',['perkuliahan/detail-perkuliahan','id'=>$model['id']],['target'=>'_blank']);
                    },
                    'Fganti'=> function ($url, $model, $key) {
                        #if(!Funct::acc('/trx-finger/form-pergantian')){return false;}
                        if($model->ds_stat==1){return false;}
                        $kode= "";//str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
                        return Html::a('<i class="glyphicon glyphicon-file"></i>Form Pergantian Jadwal'
                            ,['trx-finger/form-pergantian','id' =>$model['jdwl_id'],'k'=>$kode,'view'=>'t']);
                    },
                    'ganti'=> function ($url, $model, $key){
                        #if(!Funct::acc('/rekap-absen/create-pergantian')){return false;}
                        if($model->ds_stat==1){return false;}
                        $kode= "";//str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
                        return Html::a('<i class="fa fa-pencil"></i>Input Pergantian Jadwal'
                            ,['rekap-absen/create-pergantian','id' =>$model['jdwl_id'],'k'=>$kode,'view'=>'t']);
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
            #'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
