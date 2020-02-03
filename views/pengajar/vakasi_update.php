<?php

use app\models\Funct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;

$this->title = 'Form Perubahan Vakasi';
$this->params['breadcrumbs'][] = $this->title;

$MK="";$_MK=[];
$JR="";$_JR=[];
foreach($All as $d){
	if(!isset($_MK[$d->bn->mtk_kode])){
		$_MK[$d->jdwl_id]=1;
		$MK.=" ".$d->bn->mtk_kode.":".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=" ".$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
	}
}

if(
false
#!$_SESSION['kode']
){echo"Data Matakuliah Belum Dipilih";}
else{
?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<span class="panel-title"><b><?= "No.".$model->kode_transaksi ?></b></span>
    </div>
    <div class="panel-body">
    	<table class="table table-bordered">
        	<tr><th width="150"> Bapak/Ibu Dosen</th><td><?= $model->dsn->ds_nm ?></td></tr>
        	<tr><th> Jadwal</th><td><?= Funct::HARI()[$model->jdwl_hari_].", $model->jdwl_masuk_ - $model->jdwl_keluar_ " ?></td></tr>
        	<tr><th> Matakuliah</th><td><?= substr($MK,0,-1) ?></td></tr>
        	<tr><th> Jurusan</th><td><?= substr($JR,0,-1) ?></td></tr>        
        </table>

        <?php
        if($que){
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th rowspan="2">&sum;Mahasiswa</th>
                    <th colspan="5">UTS</th>
                    <th colspan="5">UAS</th>
                </tr>
                <tr style="font-size:10px">
                    <th>&sum;TGS1</th>
                    <th>&sum;UTS</th>
                    <th>&sum;NASKAH</th>
                    <th>&sum;SUSULAN</th>
                    <th>&sum;NASKAH SUSULAN</th>
                    <th>&sum;TGS2</th>
                    <th>&sum;UAS</th>
                    <th>&sum;NASKAH</th>
                    <th>&sum;SUSULAN</th>
                    <th>&sum;NASKAH SUSULAN</th>
                </tr>
                <tr>
                    <th><?= $que['mhs']?:0 ?></th>
                    <th><?= $que['TGS1']?:0  ?></th>
                    <th><?= $que['UTS']?:0  ?></th>
                    <th><?= $que['NUTS']?:0  ?></th>
                    <th><?= $que['UTS1']?:0  ?></th>
                    <th><?= $que['NUTS1']?:0  ?></th>
                    <th><?= $que['TGS2']?:0  ?></th>
                    <th><?= $que['UAS']?:0  ?></th>
                    <th><?= $que['NUAS']?:0  ?></th>
                    <th><?= $que['UAS1']?:0  ?></th>
                    <th><?= $que['NUAS1']?:0  ?></th>
                </tr>
                </thead>
            </table>
            <?php
        }
        ?>


    </div>
</div>


<div class="panel panel-primary">
	<div class="panel-heading">
    	<span class="panel-title"><?= $this->title ?></span>
    </div>
    <div class="panel-body">
		<div class="col-sm-12">
	    
        <?php 
		//foreach($sql as $d):
		echo $this->render('_vakasi_update',[
			'mVakasi' => $mVakasi,
			'model' => $model,
			'subAkses' => $subAkses,
			'modelTransDet'=>$modelTransDet,
			'p' => $p,
			'anv'=>($modTrans->anv==1?1:0),
		]) 
		?>
		</div>
    </div>
</div>
<?php
}
?>
