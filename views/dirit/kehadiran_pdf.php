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
		<div style="font-size:6pt">
		Ket :
		<br />
		T* 		= Target Pertemuan Dalam 1 Semester<br />
		P* 		= Total Pertemuan Perkuliahan Dalam 1 Semester<br />
		%P* 	= P*/T*<br />
		%Mhs.	= AVG( %Kehadiran Mahasiswa / P*)
		<div>
		'.
		
		$data.' 
		
		<div style="width:100%;">
		<h5 style="text-align:center;font-weight:bold">Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</h5><br />
		<table class="table table-bordered table-striped" style="font-size:8pt">
		<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Matakuliah</th>
				<th rowspan="2">&sum;Mhs.</th>
				<th colspan="16">SESI</th>
				<th colspan="2">%</th></tr><tr>
				';
		for($i=1;$i<=14;$i++){echo'<th>'.$i.'</th>';}
				

		echo'<th>T*</th> <th>P*</th><th>P*</th><th>Mhs.</th></tr></thead>';
		$TP=0;
		$TM=0;
		foreach($QueKuliah as $k=>$v){
            if($HeadDs!=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)" ){
                $HeadDs=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)";
                echo"<tr><th colspan='21'>$HeadDs</th></tr>";
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
				if($i==1){echo"<td align='right'  >$d[5]</td>";}
				
				if($d[4]>0){
					if($TotP<$v['jdwl_sesi']){
						$TotP++;$TotM+=$d[6];
						$Tot+=$d[5];
					}
				}
				echo'<td align="right" style="color:'.($d[4]==1?'green;':'red').'" >'.($d[4]==1?$d[6]:'X')."</td>";
			}
			echo "
				<td align='right'>$v[jdwl_sesi]</td>
				<td align='right'>$TotP</td>
				<td align='right'>".( round((($TotP>$v[jdwl_sesi]?$v[jdwl_sesi]:$TotP)/$v[jdwl_sesi])*100))."%</td>
				<td align='right'>".round($TotM/$Tot*100)."%</td>";
				$TP+=( round((($TotP>$v[jdwl_sesi]?$$v[jdwl_sesi]:$TotP)/$v[jdwl_sesi])*100));
				$TM+=round($TotM/$Tot*100);
			echo"</tr>";
		}
		echo'
		<tr align="right"><th colspan="19"  align="right">Total %</th><th align="right">'.round($TP/$n).'</th><th align="right">'.round($TM/$n).'</th></tr>
		</table></div></div>';
	}
	?>

