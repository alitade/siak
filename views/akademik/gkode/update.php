<?php

use yii\helpers\Html;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Ubah Jadwal: ' . ' ' . $model->jdwl_id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwals', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = ['label' => $model->jdwl_id, 'url' => ['view', 'id' => $model->jdwl_id]];
$this->params['breadcrumbs'][] = 'Ubah';
$q="select dbo.validasiJadwal(jdwl_id) t from tbl_jadwal where jdwl_id='$model->jdwl_id'";
$q=Yii::$app->db->createCommand($q)->queryOne();
if(
//$q['t']>0
false
){
	echo "<div class='alert alert-success'>Jadwal sudah memiliki Peserta</div>";
}else{
?>

<div class="jadwal-update">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <table class="table">
    	<thead>
        	<tr><td>Tahun</td><td><?= $model->bn->kln->kr_kode ?></td></tr>
        	<tr><td>Dosen</td><td><?= $model->bn->ds->ds_nm ?></td></tr>
        </thead>
    </table>
    <?=
"";	?>
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
</div>
<?php
}
?>
