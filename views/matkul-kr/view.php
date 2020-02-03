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
            <span class="panel-title">Kurikulum <?= $model->aktif==1?"Aktif":"Tidak Aktif"?></span>
        </div>
        <div class="panel-body">
            <div class="col-sm-12">
                <?= (!Funct::acc('/matkul-kr/aktif')?"":($model->aktif==1?" ":Html::a("Aktifkan Paket",['aktif','id'=>$model->id],['class'=>'btn btn-success',])))
                ?><p></p>
            </div>
            <div class="col-sm-4">
                <table class="table">
                    <tr><th>Kode</th><td>:</td><td><?= $model->kode ?></td></tr>
                    <tr><th>Jurusan</th><td>:</td><td><?= \app\models\Funct::JURUSAN()[$model->jr_id] ?></td></tr>
                    <tr><th>Total SKS</th>
                        <td>:</td>
                        <td>
                            <?= $model->totSks ?> SKS
                            <?= ($SKS!=0?'<b><h4><span class="label label-danger">'.($SKS > 0?"Kurang ":"Lebih ").abs($SKS).' SKS</span></h4></b>':"" )?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-8">
                <table class="table">
                    <tr><th colspan="3">Keterangan</th></tr>
                    <tr><td colspan="3"><?= $model->ket ?></td></tr>
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
