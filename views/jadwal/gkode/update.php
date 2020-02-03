<?php

use yii\helpers\Html;
use app\models\Funct;
use yii\bootstrap\Modal;


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
?>
<div class="panel panel-primary">
	<div class="panel-heading">
        <span class="panel-title">
            <?= $model->bn->kln->kr_kode ?> | <?= $model->bn->ds->ds_nm ?> | <?= Funct::getHari()[$modInfo->jdwl_hari].', '.substr($modInfo->jdwl_masuk,0,5).'-'.substr($modInfo->jdwl_keluar,0,5) ?>
        </span>
    </div>
    <div class="panel-body">

	<?php
    if(
    $q['t']>0
    //false
    ){echo "<div class='alert alert-success'>Jadwal sudah memiliki Peserta</div>";
    }else{

        if($cBentrok>0){
            Modal::begin([
                'options'=>['tabindex' => false],
                'id'=>'modals',
                'toggleButton'=>['label'=>'<i class="fa fa-eye"></i> Daftar Jadwal Bentrok','class'=>'btn btn-danger'],
                'header'=>'Daftar Jadwal Bersisipan',
                'size'=>'modal-lg',
                'headerOptions'=>['class'=>'bg-danger'],
                'clientOptions'=>['show'=>false,],
            ]);
            echo $dataBentrok."<div class='clearfix'></div>";
            Modal::end();
        }

        ?>
        <?php if(Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

    <div class="col-sm-6"><?= $this->render('form', ['model' => $model,])?></div>
            
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

    <?php } ?>
	</div>
</div>


