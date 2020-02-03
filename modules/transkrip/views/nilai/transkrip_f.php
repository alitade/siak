<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$no1=0;$Smster=1;$Kod=[];$TotSks=0;$SumSks=0;$Row1="";$Row="";$no=0;

$Row2="";
$pembagi=0;
if($c>0){
	$c+=8;
	if($ModMhs->jr->jr_jenjang=='S1'){
		$c+=8;
	}else if($ModMhs->jr->jr_jenjang=='D3'){
		$c+=6;
	}
	$pembagi=$c/2;
	if($c%2!=0){$pembagi=($c+1)/2;}
}
//$pembagi=30;

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
				<th colspan="5" align="center">SEMESTER '.$Smster.'</th>
			</tr>';
			$no++;

		}else{
			$Row2.='
			<tr>
				<th colspan="5" align="center">SEMESTER '.$Smster.'</th>
			</tr>';
			$no++;

		}
		$Sks=0;
		$SumNil=0;
	}
	$Nil = $data['sks']*(int)$data['nilai'];
	$SumNil+=$Sum;	
	$Sks+=$data['sks'];
	
	if($no<=$pembagi){
		$Row.="
		<tr align='left' valign='top'> 
			<td valign='top'><center>$no1</center></td>
			<td valign='top'> $data[kode_mk]".($data['kat']==1?'*':"")." </td>
			<td valign='top'> ".Html::decode($data[nama_mk])." </td>
			<td valign='top'><center>$data[sks]</center></td>
			<td valign='top'><center>$data[huruf]</center></td>
		</tr>";
	}else{
		$Row2.="
		<tr align='left'> 
			<td valign='top'><center>$no1</center></td>
			<td valign='top'> $data[kode_mk]".($data['kat']==1?'*':"")." </td>
			<td valign='top'> ".Html::decode($data[nama_mk])." </td>
			<td valign='top'><center>$data[sks]</center></td>
			<td valign='top'><center>$data[huruf]</center></td>
		</tr>
		";
	}
}

?>
<table width="100%">
    <tr><td><center><div style="font-weight:bold;font-size:16px">TRANSKRIP</div></center></td></tr>
    <tr><td align="center"><div style="width:100%;font-size:12px"><?=(!empty($NoTrans)?"No : ".strtoupper($NoTrans):"")?></div></td></tr>
</table>
<p></p>
<table width="100%" style="font-size:11px">
	<tr>
    <td width="49%" valign="top" >
    	<table style="text-align:left">
        	<tr>
                <td width="1px">Nama</td>
                <td width="1px">:</td>
                <td><?= $ModMhs->mhs->people->Nama;?></td>
            </tr>
        	<tr>
                <td>NPM</td>
                <td>:</td>
                <td><?= $ModMhs->mhs_nim;?></td>
            </tr>
        	<tr>
                <td>Program&nbsp;Studi</td>
                <td>:</td>
                <td><?= \app\models\Funct::JURUSAN()[$ModMhs->jr_id];?></td>
            </tr>
        </table>
    </td>
    <td>&nbsp;</td>
    <td width="49%" valign="top">
    	<table style="text-align:left">
        	<tr>
                <td width="1px">Tempat&nbsp;&amp;&nbsp;Tanggal&nbsp;Lahir</td>
                <td width="1px">:</td>
                <td><?= $ModMhs->mhs->people->tempat_lahir.', '.app\models\Funct::TANGGAL($ModMhs->mhs->people->tanggal_lahir,2);?></td>
            </tr>
        	<tr>
                <td>Tanggal&nbsp;Lulus</td>
                <td>:</td>
                <td><?= app\models\Funct::TANGGAL($ModHead->tgl_lulus,2)?></td>
            </tr>
        	<tr>
                <td>Predikat</td>
                <td>:</td>
                <td><?= $ModHead->predikat?></td>
            </tr>
        </table>
    </td>
    </tr>
</table>

<p></p>
<table width="100%" cellpadding="0" cellspacing="0" >
	<tr>
    <td width="49%" valign="top">
		<table width="100%" border='1' class="data" cellspacing="0" >
			<tr>
				<th width="1px" align="center">NO</th>
				<th width="1px" align="center">Kode</th>
				<th align="center">Matakuliah</th>
				<th width="1px" align="center">SKS</th>
				<th width="1px" align="center">Nilai</th>
			</tr>
			<tr>
				<th colspan="5" align="center"> SEMESTER 1</th>
			</tr>
			<tr>
				<?= $Row ?>
			</tr>	
		</table>
    </td>
    <td>&nbsp;</td>
    <td width="49%" valign="top">
		<table  width="100%" border='1' class="data" cellspacing="0" >
			<tr>
				<th width="1px" align="center">NO</th>
				<th width="1px" align="center">Kode</th>
				<th align="center">Matakuliah</th>
				<th width="1px" align="center">SKS</th>
				<th width="1px" align="center">Nilai</th>
			</tr>
			<tr>
				<?= $Row2 ?>
			</tr>	
            
			<tr><th colspan="5" align="center">Judul Tugas Akhir</th></tr>
			<tr><td colspan="5" align="left"><?= $Ta ?><p></p></td></tr>
		</table>
        <table border="0" style="border:solid 1pt #000;margin-top:5px" width="100%" class="data" >
            <tr>
                <th colspan="3">Rangkuman&nbsp;Nilai </th>
            </tr>
            <tr>
                <td width="1px">Total&nbsp;SKS</td>
                <td width="1px">:</td>
                <td><?= $TotSks ?> SKS</td>
            </tr>
            <tr>
                <td width="1px">Total&nbsp;Matakuliah</td>
                <td>:</td>
                <td><?= count($ModNil) ?></td>
            </tr>
            <tr>
                <td>IPK</td>
                <td>:</td>
                <td><?= number_format(($SumSks/$TotSks),2)?></td>
            </tr>
        </table>
    </td>
    </tr>
</table>
<p>&nbsp;</p>
<table border="0" width="100%" style="font-size:12px" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="40"></td>
    	<td width="48%">Dekan</td>
    	<td></td>
    	<td width="48%">Bandung, <?= app\models\Funct::TANGGAL(date('Y-m-d'),2)?><div>Rektor Universitas Sangga Buana YPKP</div></td>
    </tr>
	<tr>
    	<td width="40"></td>
    	<td width="48%" valign="bottom" height="90"><?= $Pejabat->nama ?></td>
    	<td></td>
    	<td width="48%" valign="bottom"><?=  $Rektor->nama  ?></td>
    </tr>
</table>
