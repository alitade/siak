<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\tabs\TabsX;


/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Ubah Jadwal: ' . ' ' . $model->jdwl_id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['/jadwal/index']];
$this->params['breadcrumbs'][] = ['label' => $model->jdwl_id, 'url' => ['/jadwal/view', 'id' => $model->jdwl_id]];
$this->params['breadcrumbs'][] = 'Ubah';
$q="select dbo.validasiJadwal(jdwl_id) t from tbl_jadwal where jdwl_id='$model->jdwl_id'";
$q=Yii::$app->db->createCommand($q)->queryOne();


$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-list"></i> Daftar Matakuliah',
        //'visible'=>false,
        //'visible'=>Funct::acc('/matkul-kr/add-matkul')
        'content'=>'a'
        /*$this->render("_view",[
        'model' => $model,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ])
    */,

    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list"></i> Update Jadwal',
        'content'=>'a'
        ,

    ],
];


?>

<div class="panel panel-primary">
	<div class="panel-heading"><h4 class="panel-title"><?= $this->title ?></h4></div>
    <div class="panel-body">

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


	<?php
    if(
    $q['t']>0
    //false
    ){
        echo "<div class='alert alert-success'>Jadwal sudah memiliki Peserta</div>";
    }else{
    ?>
    <table class="table">
        <thead>
            <tr><td>Tahun</td><td><?= $model->bn->kln->kr_kode ?></td></tr>
            <tr><td>Dosen</td><td><?= $model->bn->ds->ds_nm ?></td></tr>
        </thead>
    </table>
	<?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
        
    <div class="col-sm-6">
    <?= $this->render('form', [
        'model' => $model,
    ]) ?>
    </div>
            
    <div class="col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">INFO MATAKULIAH GABUNGAN</div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                    <tr>
                        <td>No</td>
                        <td>Maktul</td>
                        <td>Jurusan</td>
                    </tr>
                    </thead>
                    <tbody>
                <?php $n=0; foreach($gabung as $data):$n++;?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= $data->bn->mtk->mtk_kode.' :'.$data->bn->mtk->mtk_nama." ( ".$data->jdwl_kls." )" ?></td>
                        <td><?= $data->bn->kln->jr->jr_jenjang.'- '.$data->bn->kln->jr->jr_nama." ( ".$data->bn->kln->pr->pr_nama." )" ?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
        
        <?php
        }
        ?>
	</div>
</div>


