<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="col-sm-6">
    <table class="table">
    	<tr>
        	<th colspan="3"><?= $ModBn->kln->kr_kode." ( ".$ModBn->ds->ds_nm." : ".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama." ($model->jdwl_kls) ) " ?></th>
        </tr>
    	<tr>
        	<th>Jadwal Awal</th>
        	<th>:</th>
        	<th><?= \app\models\Funct::HARI()[$model->jdwl_hari]." ,".$model->jdwl_masuk.'-'.$model->jdwl_keluar ?></th>
        </tr>
    </table>
    </div>
    <div style="clear:both"></div>
	<?= 
        $this->render('schedule__form', [
        'model' => $model,
        'mtk' => $ModBn->mtk,
    ]) 
	?>	

<?php 
if($Q && $Q!=""){
	$ket="Normal";
	if($data['tp']==2){$ket="Pindahan";}

?>	
	<table class="table table-bordered">
    	<thead>
        	<tr>
            	<th>#</th>
            	<th>Jadwal</th>
            	<th>Nama</th>
            	<th>Matakuliah</th>
            	<th>Dosen</th>
             </tr>
        </thead>
	<? $n=0; foreach($Q as $data): $n++ ?>
    	<tr>
			<td><?= $n ?></td>
        	<td><?= \app\models\Funct::HARI()[$data[hari]].", ".substr($data['masuk'],0,5)."-".substr($data['keluar'],0,5)." <b>($ket)</b> "?></td>
            <td><?= "<b>($data[username])</b> ".$data['nama'] ?></td>
            <td><?= $data['matkul'] ?></td>
            <td><?= $data['dosen'] ?></td>
        </tr>            
    <?	endforeach;?>
	
<? } ?>
	</table>
</div>
