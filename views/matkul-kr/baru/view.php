<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\tabs\TabsX;
use app\models\Funct;


$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum Matakuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$SKS=$model->totSks - $SKS;
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-list"></i> Daftar Matakuliah',
        //'visible'=>false,
        'content'=>$this->render("_view",[
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]),

    ],
    [
        'label'=>'<i class="glyphicon glyphicon-plus"></i> Tambah Matakuliah Baru',
        'content'=>$this->render("mtk_form",[
            'model' => $ModMtk,
            'KR'=>$model,
        ]),
        'active'=>$ActiveForm,
		'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
    [
        'label'=>'<i class="fa fa-users"></i> Mahasiswa',
        'content'=>$this->render("/matkul-kr/mhs",[
            'dataProvider' => $dataMhs,
            'searchModel' => $searcMhs,
        ]),
        'active'=>$ActiveForm,
        'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],

    [
        'label'=>'<i class="glyphicon glyphicon-list"></i> Sub Kurikulum',
        'visible'=>$viewSub,
        'content'=>$viewSub?
            $this->render("sub_index",[
            //'model' => $model,
            'dataProvider' => $subData,
            'searchModel' => $searchSub,
        ]):"",

    ],
];


?>
<p></p>
<div>
    <div class="panel panel-<?= $model->aktif==1?"success":"danger"?>">
        <div class="panel panel-heading">
            <span class="panel-title" style="font-weight: bold">Kurikulum <?= Funct::JURUSAN()[$model->jr_id]." [ $model->kode | ".($model->aktif==1?"Aktif":(!Funct::acc('/matkul-kr/aktif')?"":Html::a("Aktifkan",['aktif','id'=>$model->id],['class'=>'badge','style'=>'color:#fff;font-weight:bold'])))." ]"
                ?></span>
            <div class="pull-right">
            <?=
               Html::a("&sum; <i class='fa fa-users'></i> ".($model->totSks?:0),['mhs','id'=>$model->id],['class'=>'badge','style'=>'background:green']).
            " | &sum;SKS ".($model->totSks?:0).""
            ?>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-sm-12" style="margin-top:-20px">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>
                            Keterangan:<br>
                            <?= $model->ket ?>
                        </th>
                    </tr>

                    </thead>
                </table>

            </div>

            <div class="col-sm-12">
                <?=
                 TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false,
                    'bordered'=>true,
                    //'sideways'=>TabsX::POS_LEFT,
                ]);
                ?>
            </div>
        </div>
    </div>



</div>
