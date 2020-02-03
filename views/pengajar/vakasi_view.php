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




$this->title = 'View Faktur';
$this->params['breadcrumbs'][] = $this->title;


$MK="";$_MK=[];
$JR="";$_JR=[];
foreach($All as $d){
	if(!isset($_MK[$d->bn->mtk_kode])){
		$_MK[$d->bn->mtk_kode]=1;
		$MK.=$d->bn->mtk_kode." :".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
	}
}
$stat="";
if($modTrans->status==0){$stat="(DRAFT)";}
?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<span class="panel-title">
        <b>
		<?= $model->bn->ds->ds_nm." (".Funct::HARI()[$model->jdwl_hari].', '.$model->jdwl_masuk.'-'.$model->jdwl_keluar.")" ?>
        </b>
        </span>
    </div>
    <div class="panel-body">
    	<table class="table table-bordered">
        	<tr><th> Kode/Matakuliah</th><th></th><td><?= substr($MK,0,-1) ?></td></tr>
        	<tr><th> Program Studi</th><th></th><td><?= substr($JR,0,-1) ?></td></tr>
        </table>
    	<?php
        if($sql){
		?>
        <table class="table table-bordered">
        	<thead>
            <tr>
					<th> &sum;Mhs </th>
					<th> &sum;Tgs1 </th>
					<th> &sum;UTS</th>
					<th> &sum;Tgs2</th>
					<th> &sum;UAS</th>
            </tr>
            </thead>
            <tbody>
            <?php
			foreach($sql as $d){
				echo'
				<tr>
				<td>'.$d['totMhs'].' </td>
				<td>'.$d['tgs1'].' </td>
				<td>'.$d['uts'].' </td>
				<td>'.$d['tgs2'].' </td>
				<td>'.$d['uas'].' </td>
				</tr>';
			}
			?>
            </tbody>
        </table>
        <?php	
		}
        ?>
        
		<div class="alert alert-info" style="padding:2px;font-weight:bold">
        	Ket *Tipe :<br />1:UTS, 2:UAS, 1.1:UTS Susulan, 2.1 : UAS Susulan
        </div>
        <table class="table table-bordered">
        	<thead>
			<tr>
            	<th width="1%">No</th>
            	<th>Tanggal</th>
            	<th>No Faktur</th>
            	<th>Tipe</th>
            	<th>Operator</th>
            	<th>Status</th>
            	<th>cetak</th>
            	<th></th>
            </tr>
            </thead>
            <tbody>
            <?php $n=0; foreach($TRANSAKSI as $d):$n++;
			$stat="";
			$link=Html::a('<i class="fa fa-eye"></i> '.$d->kode_transaksi,['/pengajar/vakasi-faktur-view','id'=>$d->kode_transaksi],['target'=>'_blank']);
			if($d->status==0){
				$stat='<span class="label label-info">DRAFT</span>';
				$link=Html::a('<i class="fa fa-pencil"></i> '.$d->kode_transaksi,['/pengajar/vakasi-faktur','id'=>$d->kode_transaksi],['target'=>'_blank']);
			}
			
			$tipe="";
			$Ttgs1=0;$Ttgs2=0;$Tuts=0;$Tuas=0;
			foreach($d->dat as $d1){$Ttgs1+=$d1->tgs1;$Ttgs2+=$d1->tgs2;$Tuts+=$d1->uts;$Tuas+=$d1->uas;}
			$UTS1="";
			$UTS2="";
			foreach($d->det as $d1){
				if($d1->kd_prod=='UTS1'||$d1->kd_prod=='NUTS1'){$UTS1=' 1.1,';}
				if($d1->kd_prod=='UAS1'||$d1->kd_prod=='NUAS1'){$UAS1=' 2.1,';}
				
			}

			if($Ttgs1>0||$Tuts>0){$tipe.=" 1,";}
			$tipe.=$UTS1;
			if($Ttgs2>0||$Tuas>0){$tipe.=" 2,";}
			$tipe.=$UAS1;
			
			?>
			<tr>
            	<td><?= $n ?></td>
            	<td><?= $d->tgl ?></td>
            	<td><?= "$link " ?></td>
            	<td><?= substr($tipe,0,-1) ?></td>
            	<td><?= $d->c->name?></td>
            	<td><?= $stat ?></td>
            	<td><?= $d->cetak ?></td>
            	<td><?= '' ?></td>
            </tr>	
                
			<?php endforeach; ?>
            </tbody>
        </table>
        
    </div>
</div>

