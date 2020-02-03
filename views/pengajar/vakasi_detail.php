<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;
use app\models\Funct;


$this->title = 'Detail Vakasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
    <h4 class="panel-title"><?= $this->title?> <?= $ModBn->kln->kr_kode ?> | <?= $ModBn->ds->ds_nm ?></h4>
    </div>
    <div class="panel-body">


<?php

		$DATA="";$_n=0;
		foreach($ModBn_ as $d){
			foreach($d->jdw as  $d_){
				$cJadwal="
					<tr>
						<th>Jurusan</th>
						<th>Matakuliah</th>
                            <th>&sum;Mhs</th>
                            <th>&sum;Tgs1</th>
                            <th>&sum;UTS</th>
                            <th>&sum;Tgs2</th>
                            <th>&sum;UAS</th>
                            <th> </th>
					</tr>
				";
				$jam="";
				if(!isset($_G[$d_->GKode])){
					$_G[$d_->GKode]=1;$Gkode.="'$d_->GKode',";
					$dJadwal = \app\models\Jadwal::find()
					->select([
						'tbl_jadwal.jdwl_id','tbl_jadwal.bn_id','tbl_jadwal.jdwl_kls','tbl_jadwal.jdwl_hari','tbl_jadwal.jdwl_masuk','tbl_jadwal.jdwl_keluar',
						'app'=>"sum(iif(isnull(krs_stat,0)=1,1,0))", 
						'tgs1'=>"sum(iif(isnull(krs_tgs1,0)>1,1,0))", 
						'tgs2'=>"sum(iif(isnull(krs_tgs2,0)>1,1,0))", 
						'tgs3'=>"sum(iif(isnull(krs_tgs3,0)>1,1,0))", 
						'quiz'=>"sum(iif(isnull(krs_quis,0)>1,1,0))", 
						'uts'=>"sum(iif(isnull(krs_uts,0)>1,1,0))", 
						'uas'=>"sum(iif(isnull(krs_uas,0)>1,1,0))",				
						'totMhs'=>"count(krs.jdwl_id)",		
					])
					->innerJoin("tbl_krs krs","(krs.jdwl_id=tbl_jadwal.jdwl_id and isnull(krs.RStat,0)=0)")
					->innerJoin("tbl_bobot_nilai bn","(bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0) and bn.ds_nidn=$ModBn->ds_nidn")
					->where("GKode='$d_->GKode' and isnull(tbl_jadwal.RStat,0)=0 ")
					->groupBy('
						tbl_jadwal.jdwl_id,tbl_jadwal.bn_id,tbl_jadwal.jdwl_kls,
						tbl_jadwal.jdwl_hari,tbl_jadwal.jdwl_masuk,tbl_jadwal.jdwl_keluar')
					->all();
					#/*
					
					$Ttgs1=0;$Ttgs2=0;$Tuts=0;$Tuas=0;$Tmhs=0;
					$_Ttgs1=0;$_Ttgs2=0;$_Tuts=0;$_Tuas=0;
					$TOT	=0;
					$TOT_	=0;
					foreach($dJadwal as $dj){
						$sty='style="background:green;font-weight:bold;color:#fff"';
						$_n++;
						$_Ttgs1+=(isset($kdVk[$dj->jdwl_id]['tgs1'])?0:$dj->tgs1);
						$_Ttgs2+=(isset($kdVk[$dj->jdwl_id]['tgs2'])?0:$dj->tgs2);
						$_Tuts+=(isset($kdVk[$dj->jdwl_id]['uts'])?0:$dj->uts);
						$_Tuas+=(isset($kdVk[$dj->jdwl_id]['uas'])?0:$dj->uas);
						
						$Ttgs1+=$dj->tgs1;
						$Ttgs2+=$dj->tgs2;
						$Tuts+=$dj->uts;
						$Tuas+=$dj->uas;
						$Tmhs+=$dj->totMhs;
						$jam=\app\models\Funct::HARI()[$dj->jdwl_hari].", $dj->jdwl_masuk - $dj->jdwl_keluar";
						$cJadwal.="
						<tr>
							<td>".$dj->bn->kln->jr->jr_jenjang." ".$dj->bn->kln->jr->jr_nama." (".$dj->bn->kln->pr->pr_nama.") </td>
							<td>".$dj->bn->mtk->mtk_kode." ".$dj->bn->mtk->mtk_nama."(".$dj->jdwl_kls."|".$dj->bn->mtk->mtk_sks.")</td>
							<td>".$dj->totMhs."</td>
							<td ".(isset($kdVk[$dj->jdwl_id]['tgs1'])?$sty:"").">".$dj->tgs1."</td>
							<td ".(isset($kdVk[$dj->jdwl_id]['uts'])?$sty:"").">".$dj->uts."</td>
							<td ".(isset($kdVk[$dj->jdwl_id]['tgs2'])?$sty:"").">".$dj->tgs2."</td>
							<td ".(isset($kdVk[$dj->jdwl_id]['uas'])?$sty:"").">".$dj->uas."</td>
							<td>".Html::checkbox('id[]',false,['label' => false,'value'=>$dj->jdwl_id])."</td>
						</tr>";
					}
					$TOT_=$_Ttgs1+$_Ttgs2+$_Tuts+$_Tuas;
					$TOT=$Ttgs1+$Ttgs2+$Tuts+$Tuas;
					$BTN="";
					
					if($TOT>0){

						$BTN=(
							$TOT_ > 0?Html::submitButton('<i class="fa fa-plus"></i> Faktur Vakasi', ['class' => 'btn btn-success',]):
							Html::submitButton('<i class="fa fa-plus"></i> Faktur Vakasi Susulan', ['class' => 'btn btn-success','name'=>'s'])
						);
						if($Tmhs<5){
							$BTN=Html::submitButton('<i class="fa fa-plus"></i> Faktur Vakasi Anvulen', ['class' => 'btn btn-success','name'=>'av']);
							if($TOT_==0){$BTN="";}
						}	
					}	
					
					
					$cJadwal.="
					<tr>
						<th rowspan='3'> $BTN "
						.Html::a('<i class="fa fa-eye"></i> Detail',['/pengajar/vakasi-view','id'=>$d_->GKode],['class' => 'btn btn-primary',])
						." </th>
						<th style='text-align:right'>Total Input</th>
						<th>".$Tmhs."</th>
						<th>".$Ttgs1."</th>
						<th>".$Tuts."</th>
						<th>".$Ttgs2."</th>
						<th>".$Tuas."</th>
					</tr>
					<tr>
						<th style='text-align:right'>Terbayar</th>
						<th> </th>
						<th>".$_Ttgs1."</th>
						<th>".$_Tuts."</th>
						<th>".$_Ttgs2."</th>
						<th>".$_Tuas."</th>
					</tr>
					<tr>
						<th style='text-align:right'>Sisa</th>
						<th> </th>
						<th>".$_Ttgs1."</th>
						<th>".$_Tuts."</th>
						<th>".$_Ttgs2."</th>
						<th>".$_Tuas."</th>
					</tr>
					
					";

					$form = ActiveForm::begin([
						'type'=>ActiveForm::TYPE_HORIZONTAL,
						'action'=>Url::to(['/pengajar/vakasi-proc','kd'=>$d_->GKode]),
						'options'=>['target'=>'_blank',],						
					]);
					$Htitle=''.$d_->GKode." ($jam)".'';
					if(isset($kdVk['D'][$d_->GKode])&&$kdVk['D'][$d_->GKode]==0){
						$Htitle='<h4><span class="label label-warning"> <i class="fa fa-exclamation-circle"></i> '.$d_->GKode." ($jam)".'</span></h4>';
						$Htitle=Html::a($Htitle,['/pengajar/vakasi-view','id'=>$d_->GKode],['target'=>'_blank',]);
					}
					
					echo ' 
					<table class="table table-borderer">
					<thead>
					<tr><th>'.$Htitle.'</th></tr>
					</thead>
					<tbody>'.$cJadwal.'</tbody>
					</table>';
					ActiveForm::end();
				}
			}
		}

//echo $DATA;
?>
    </div>
</div>

