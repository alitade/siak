<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\tabs\TabsX;


$this->title = $Parent->kode;
$this->params['breadcrumbs'][] = ['label' => 'Sub Kurikulum', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$SKS=$model->totSks - $SKS;
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-list"></i> Daftar Matakuliah',
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
        'active'=>$ActiveForm
    ],
];
?>
<p></p>
<div>
    <div class="panel panel-<?= $Parent->aktif==1?"success":"danger"?>">
        <div class="panel panel-heading">
            <span class="panel-title"> Paket <?= $Parent->aktif==1?"Aktif":"Tidak Aktif"?>
            <?=
            " <i>( Sub Kurikulum $Parent->kode,".\app\models\Funct::JURUSAN()[$Parent->jr_id]." $Parent->totSks SKS ) </i> ".
            ($SKS!=0?'<b><h4><span class="label label-danger">'.($SKS>0?"Kurang ":"Lebih ").abs($SKS).' SKS</span></h4></b>':"" )
            ?>
            </span>
        </div>
        <div class="panel-body">
            <div class="col-sm-12">
                <?=
                $Parent->aktif==1?" ":
                Html::a("Aktifkan Paket",['aktif','id'=>$Parent->id],['class'=>'btn btn-success',])
                ?><p></p>
            </div>
            <div class="col-sm-12">
                <table class="table">
                    <tr><th width="90">Konsentrasi</th><td width="1%">:</td><td><?= $model->pr->pr_nama." | ".$model->pr->pr_en ?></td></tr>
                    <tr><th>Keterangan</th><td>:</td><td><?= $model->ket ?></td></tr>
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
