<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\tabs\TabsX;

$this->title = $model->bn->kln->kr->kr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label'=>'<i class="fa fa-list"></i> Info Jadwal',
        'content'=>$this->render("/jadwal/_jadwal",[
                'model' => $model,
                'modGab' => $modGb,
                'Detail'=>$Detail,
                'cSesi'=>$cSesi,
            ]),

        #'active'=>$ActiveForm,
        #'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
    [
        'label'=>'<i class="fa fa-list"></i> Info Perkuliahan',
        'content'=>$this->render("/jadwal/_perkuliahan",[
            'model' => $model,
            'modGab' => $modGb,
            'Detail'=>$Detail,
            'cSesi'=>$cSesi,
            ]),
        #'active'=>$ActiveForm,
        #'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
];

?>
<div class="panel panel-primary">
	<div class="panel-heading"><span class="panel-title">
    <?= Funct::JURUSAN()[$model->bn->kln->jr_id]." (".Funct::PROGRAM()[$model->bn->kln->pr_kode].") " ?> | Semester <?= $model->bn->kln->kr->kr_nama ?><br />
    <?= ""#$model->bn->ds->ds_nm ?>
    </span></div>

    <div class="panel-body">
       <h4><?= $model->bn->ds->ds_nm ?> :
        <?= Funct::getHari()[$model->jdwl_hari].", $model->jdwl_masuk - $model->jdwl_keluar" ?>
        (<?= $model->rg->rg_nama ?>)
       </h4>
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
