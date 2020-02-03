<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;
use app\models\Funct;


$this->title = 'Detail Jadwal Pengajar';
$this->params['breadcrumbs'][] = ['label' => 'Pengajar', 'url' => ['/pengajar/index']];
$this->params['breadcrumbs'][] = $this->title;

$JR = array_flip($subAkses['jurusan']);
?>
<?php
if(Funct::acc('/pengajar/jadwal-create')):	
?>
	<?= 
		$this->render($ViewForm, [
		'model' => $model2,
		'mod' => $ModBn,
		'pid' => $pid,
		'qBentrok'=>$qBentrok,
	]) 
	?>
	<div class="col-lg-12">
	<?php if(Yii::$app->session->hasFlash('error')): ?>
		<div class="alert alert-danger" role="alert">
			<?= Yii::$app->session->getFlash('error') ?>
		</div>
	<?php endif; ?>
	</div>
<?php
endif;
?>


<div class="panel panel-primary">
	<div class="panel-heading">
    <h4 class="panel-title"><?= $this->title?> <?= $ModBn->kln->kr_kode ?> | <?= $ModBn->ds->ds_nm ?></h4>
    </div>
    <div class="panel-body">
    <?php if($qDetJdwl):?>
    <div> <h4>List Jadwal</h4>
    <?php
	$n=0; 
	$Lbn="";$Ljd="";$Ljr="";$Lh="";
	$data="
	<div>
	<table class='table'>
	<tr>
		<th> Jadwal </th>
		<th> Jurusan </th>
		<th> Matakuliah </th>
		<th> APP | &sum; </th>
		<th>  </th>
	</tr>";

	$n=0; 
	$Lbn	="";$Ljd="";$Ljr="";$Lh="";
	$data	="<div><table>";
	$n1=0;
	$hide=0;
	foreach($qDetJdwl as $d){
		$n++;
		
		if($d['totMhs']>0){$hide++;}
		if($Lbn!=$d['GKode']){
			$n1=0;
			$Lbn=$d['GKode'];
			$data.="
			</table>
			</div>
			<div class='col-sm-12'>
			<h3>".$Lbn." "
			.
			(!Funct::acc('/pengajar/jadwal-sub-create')?"":
			Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Sub Jadwal',["pengajar/view",'id'=>$d['bn_id'],'pid'=>$d['jdwl_id']],['class'=>'btn btn-success']))."</h3>
			<table class='table table-bordered'>
				<tr>
					<th> Jadwal | Ruangan</th>
					<th> Jurusan </th>
					<th> Matakuliah (KLS | SKS)</th>
					<th> APP | &sum; </th>
					<th></th>
				</tr>
			";
		}

		$jdwl=explode("|",$d['jadwal']);
		$jd = "";
		foreach($jdwl as $k=>$v){
			$Info=explode('#',$v);
			$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
			$jd.=($d['s']>0 && $Info[0]==$d['id']?'<i class="glyphicon glyphicon-lock"></i> ':
				(!Funct::acc('/pengajar/del-ch')?"":
					Html::a('<i class="glyphicon glyphicon-trash"></i>',['/pengajar/del-ch','id'=>$Info[0]]
					,['onClick'=>'return confirm("Hapus Jadwal Hari '."$ket".'")'])
				))//." ".Html::a('<i class="glyphicon glyphicon-pencil"></i>',['#'])
				.($Info[0]==$d['id']?" <b>$ket</b>":" $ket")
				
				."<br />";
		}
		$jdwl=$jd;

		if($Ljr!=$Lbn.'|'.$d['jr_jenjang']."|".$d['jr_nama']."|".$d['pr_kode']){			
			$Ljr=$Lbn.'|'.$d['jr_jenjang']."|".$d['jr_nama']."|".$d['pr_kode'];
			$data.='<tr>'
				.($n1>0?"":'<td rowspan="24">'.$jdwl.'</td>')
				.'<td>'
				.$d['jr_jenjang']." ".$d['jr_nama']
				." (".$d['pr_nama'].")<br />".'</td>
				<td>'.$d['mtk_kode']." ".$d['mtk_nama']." ($d[jdwl_kls] | $d[mtk_sks])".' </td>
				<td>'.$d['app'].' | '.$d['totMhs'].' </td>
				<td>'
				.($d['totMhs']>0? "":
				(
					!Funct::acc('/pengajar/del-gp') || ($JR && !isset($JR[$d['jr_id']]))?"":
					
					Html::a('<i class="glyphicon glyphicon-trash"></i>',['pengajar/del-gp','id'=>$d['id']]
						,[
							'onClick'=>
							'return confirm("Hapus Jadwal '." $d[jr_jenjang] $d[jr_nama]|$d[mtk_kode]-$d[mtk_nama] ($d[jdwl_kls] | $d[mtk_sks])".'")'
						]
					))
				)				
				.'</td>
			</tr>';
			
		}
		$n1++;

	}
	
	echo $data."</table></div>";
	?>
    </div>
    <br />
    <?php
	endif;
	?>
    </div>
    


</div>
