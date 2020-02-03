<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
?>
    <?php 
	if($KrKd>0){
		$data ='';
		$n=0;
		$hd="";
		$bd='';
		//print_r($QueKuliah);
		echo '<div class="col-xl-12">
		Ket Kolom Sesi:<br />
			x(Jumlah Mahasiswa)<br />
			HH:mm-HH:mm (Jam Perkuliahan)<br />
			HH:mm-HH:mm (Jam Kehadiran)<br />
			mm\'|mm\' ( Durasi Keterlambatan Dalam Menit | Durasi Perkuliahan Dalam Menit)<br />
			<span style="background:green;collor:white;border:none;font-size:13px;padding:2px">Hadir</span>
			<span style="background:red;collor:black;border:none;font-size:13px;padding:2px">Tidak Hadir</span> 
		'.
		
		$data.'
		<div style="width:100%;">
		<h5 style="text-align:center;font-weight:bold">Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</h5><br />
		<table class="table table-bordered table-striped"  style="font-size:11pt;font-family:\'Times New Roman\'">
		<thead>
			<tr>
				<th rowspan="2">#</th>
				<th rowspan="2">Matakuliah</th>
				<th colspan="14">SESI</th>
			<tr>
				';
		for($i=1;$i<=14;$i++){echo'<th style="width:30pt">'.$i.'</th>';}
		echo'</tr></thead>';
		$TP=0;
		$TM=0;
		$HeadDs="";
		foreach($QueKuliah as $k=>$v){
			
			if($HeadDs!=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)" ){
				$HeadDs=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)";
				echo"<tr><td colspan='15'>$HeadDs</td></tr>";
			}
			$n++;
			$fid=$v['fid'];
			echo"<tr>
				<td>$n</td>
				<td>$v[mtk_kode]: $v[mtk_nama] ($v[jdwl_kls])</td>
			";
			
			$TotP=0;
			$TotM=0;
			$Tot=0;
			for($i=1;$i<=14;$i++){
				#   0        1       2     3        4      5      6   7     8
				#DsMasuk|DsKeluar|Menit|DsGetFid|DsStat|TotMhs|Total|Tsesi|tpr 
				$d=explode('|',$v[$i]);
				$d[0]=substr($d[0],0,5);
				$d[1]=substr($d[1],0,5);
				if($i==1){echo"<!-- td>$d[5]</td -->";}
				if($d[4]>0){
					if($TotP < $d[7]){
						$TotP++;$TotM+=$d[6];
						$Tot+=$d[5];
					}
				}
				echo'<td style="color:'.($d[4]==='0' || !$d[4]?'red;':($d[4]==='1'?'green;':'red;')).'">'
				.($d[4]==0?'X':
				'<span class="label" style="color:inherit;font-size:12px;border:none;">'.$d[6]."</span><br />"
				.'<span class="label" style="color:inherit;font-size:12px;border:none;">'.($d[10]?$d[10]:"-")."-".($d[11]?$d[11]:"-")."</span><br />"
				.'<span class="label" style="color:inherit;font-size:12px;border:none;">'.($d[0]?$d[0]:"-")."-".($d[1]?$d[1]:"-")."</span><br />"
				.'<span class="label" style="color:inherit;font-size:12px;border:none;">'
					.( $d[9] ? '<span style="color:'.($d[9]>0?'red;':'green;').'">'.$d[9]."</span>" :"-")." | ".($d[2]?$d[2]:"-")
				."</span><br />"
				)."
				</td>";
			}
				$TP+=( round((($TotP>$d[7]?$d[7]:$TotP)/$d[7])*100));
				$TM+=round($TotM/$Tot*100);
			echo"</tr>";
		}
		echo'</tr>
		</table></div></div>';
	}
	?>

