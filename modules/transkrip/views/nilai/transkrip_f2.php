<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;

$no1=0;$Smster=1;$Kod=[];$TotSks=0;$SumSks=0;$Row1="";$Row="";$no=0;

$Row2="";
$pembagi=0;
if($c>0){
	$c+=4;
	if($ModMhs->jr->jr_jenjang=='S1'){
		$c+=8;
	}else if($ModMhs->jr->jr_jenjang=='D3'){
		$c+=6;
	}
	
	$pembagi=$c/2;
	if($c%2!=0){$pembagi=($c+1)/2;}
	if($pembagi<40){
		$pembagi=40;
	}
}
$Sks=0;
$Nil=0;
// var_dump($ModNil);
foreach($ModNil as $data){
	$Sum = $data['sks']*(int)$data['nilai'];
	$SumSks+=$Sum;	

	$TotSks+=$data['sks'];
	
	$Kod[$data['kode_mk'].$data['huruf']]='';
	$no1++;
	$no++;
	$a=filter_var($data['kode_mk'], FILTER_SANITIZE_NUMBER_INT);
	$SmsterSub=substr($a,0,1);	
	if($Smster!=$SmsterSub){
		$Smster=$SmsterSub;
		if($no<=$pembagi){$Row.='
			<tr>
				<th colspan="5" align="center" style="border:solid 1px #000;"> SEMESTER '.$Smster.'</th>
			</tr>';
		}else{
			$Row2.='
			<tr>
				<th colspan="5" align="center" style="border:solid 1px #000;"> SEMESTER '.$Smster.'</th>
			</tr>';
		}
		$no++;
		$Sks=0;
		$SumNil=0;
		$Sks=0;
		$SumNil=0;
	}
//	$LenStr=(int )(85/(int) strlen('Praktika Teknik Perdagangan Internasional'));	
//	if($LenStr>1){$no +=($LenStr-1);}
	$Nil = $data['sks']*(int)$data['nilai'];
	$SumNil+=$Sum;	
	$Sks+=$data['sks'];
	
	$data[kode_mk] = str_replace(" ","",$data[kode_mk]);
	
	if($no<=$pembagi){
		$Row.="
		<tr align='left'> 
			<td valign='top' align='center' class='bdr'>$no1</td>
			<td valign='top' class='bdr' nowrap>$data[kode_mk]".($data['kat']==1?'*':"")."</td>
			<td valign='top' class='bdr' nowrap='nowrap'>".Html::decode($data[nama_mk])."</td>
			<td valign='top' align='center' class='bdr'>$data[sks]</td>
			<td valign='top' align='center'>$data[huruf]</td>
		</tr>";
	}else{
		$Row2.="
		<tr align='left'>  
			<td valign='top' align='center' class='bdr'>$no1</td>
			<td valign='top' class='bdr' nowrap>$data[kode_mk]".($data['kat']==1?'*':"")."</td>
			<td valign='top' class='bdr' nowrap='nowrap'>".Html::decode($data[nama_mk])."</td>
			<td valign='top' align='center' class='bdr'>$data[sks]</td>
			<td valign='top' align='center'>$data[huruf]</td>
		</tr>
		";
	}
}

?>
<table width="100%">
    <tr><td align="center">
    	<div style="font-weight:bold;font-size:18px;margin-bottom:2px;border-bottom:solid 1px #000">TRANSKRIP</div>
		<p></p>        
        <div style="width:100%;font-size:12px"><?=(!empty($NoTrans)?"No : ".strtoupper($NoTrans):"")?></div>
    </td></tr>
</table>
<p></p>
<table width="100%" style="font-size:12px" cellpadding="1">
	<tr>
    <td width="49%" valign="top" >
    	<table>
        	<tr>
                <td width="1px">Nama</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs->people->Nama;?></td>
            </tr>
        	<tr>
                <td>NPM</td>
                <td>&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs_nim;?></td>
            </tr>
        	<tr>
                <td>ProgramStudi</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= \app\models\Funct::JURUSAN()[$ModMhs->jr_id];?></td>
            </tr>
        </table>
    </td>
    <td></td>
    <td width="49%" valign="top">
    	<table style="text-align:left" >
        	<tr>
                <td width="1px">Tempat&nbsp;&amp;&nbsp;Tanggal&nbsp;Lahir</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModMhs->mhs->people->tempat_lahir.', '.app\models\Funct::TANGGAL($ModMhs->mhs->people->tanggal_lahir,2);?></td>
            </tr>
        	<tr>
                <td>TanggalLulus</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= app\models\Funct::TANGGAL($ModHead->tgl_lulus,2)?></td>
            </tr>
        	<tr>
                <td>Predikat</td>
                <td width="1px">&nbsp;:&nbsp;</td>
                <td><?= $ModHead->predikat?></td>
            </tr>
        </table>
    </td>
    </tr>
</table>
<p></p>
<columns column-count='1'>
<table width="100%" autosize="0" border="0">
	<tr>
    <td width="49%" valign="top">
		<table border='0' class="data" cellpadding="0" cellspacing="0" autosize="0">
			<tr>
				<th width="1px" align="center" class="bd">NO</th>
				<th width="1px" align="center" class="bd">Kode</th>
				<th align="center" class="bd">Matakuliah</th>
				<th width="1px" align="center" class="bd">SKS</th>
				<th width="1px" align="center" class="bd">Nilai</th>
			</tr>
			<tr>
				<th colspan="5" align="center" style="border:solid 1px #000;"> SEMESTER 1</th>
			</tr>
			<tr>
				<?= $Row ?>
			</tr>	
		</table>
    </td>
    <td style="width:5mm"> </td>
    <td width="49%" valign="top">
		<table  width="100%" border='0' class="data" cellpadding="0" cellspacing="0" autosize="0">
			<tr>
				<th width="1px" align="center" class="bd">NO</th>
				<th width="1px" align="center" class="bd">Kode</th>
				<th align="center" class="bd">Matakuliah</th>
				<th width="1px" align="center" class="bd">SKS</th>
				<th width="1px" align="center" class="bd">Nilai</th>
			</tr>
			<tr>
				<?= $Row2 ?>
			</tr>	
			<tr><th colspan="5" align="center" style="border-top:solid 1pt #000;border-bottom:solid 1pt #000">Judul Tugas Akhir / Skripsi</th></tr>
			<tr><td colspan="5" align="left" style="padding:1mm"><?= $Ta ?></td></tr>
		</table>
        
        <table width="100%" border='0' class="data" cellpadding="0" cellspacing="0" autosize="0">
        	<tr></tr>
            <tr>
                <th width="50%" align="left">SKS : <?= $TotSks ?> SKS</th>
                <th width="50%" align="right">IPK : <?= number_format(($SumSks/$TotSks),2)?></th>
            </tr>
        </table>
    </td>
    </tr>
</table>
<table width="100% !important" border="0" style="margin-top:2px;font-size:12px;height:650mm" autosize="0">
	<tr style="">
    <td valign="top" style="width:72mm;">
    	<table>
        	<tr><td><br />Dekan</td></tr>
        	<tr><td height="100"></td></tr>
        	<tr><td><?= $Pejabat->nama ?></td></tr>
        </table>
    </td>
    <td style="width:32mm;height:40mm;border:solid 1px #000" align="center">
    	[4x6]
    </td>
    <td align="right"  valign="top">
    	<table>
        	<?php 
			$tglCetak = date('Y-m-d');
			if($ModHead->tgl_cetak){
				$tglCetak=date('Y-m-d',strtotime($ModHead->tgl_cetak));


			}

            $date=date_create($ModHead->tgl_lulus);
            date_add($date,date_interval_create_from_date_string("7 days"));

			?>
        	<tr><td align="left"><br />Bandung, <?= app\models\Funct::TANGGAL(date_format($date,"Y-m-d"),2);?><div>Rektor Universitas Sangga Buana YPKP</div></td></tr>
        	<tr><td height="100"></td></tr>
        	<tr><td align="left"><?=  $Rektor->nama  ?></td></tr>
        </table>
    </td>
    </tr>
</table>
<columnbrak/>