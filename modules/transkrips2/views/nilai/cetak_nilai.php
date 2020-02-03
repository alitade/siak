<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;

$no1=0;
$Smster=1;
$Kod=[];
$TotSks=0;
$SumSks=0;
$Row1="";

// var_dump($ModNil);
foreach($ModNil as $data){
	$Sum = $data['sks']*(int)$data['nilai'];
	$SumSks+=$Sum;
	$TotSks+=$data['sks'];
	$Kod[$data['kode_mk'].$data['huruf']]='';
	$no1++;
	$SmsterSub=substr($data['kode_mk'],2,1);
	if($Smster!=$SmsterSub){
		$Smster=$SmsterSub;
		$Row1.='<tr><th colspan="5" style="text-align: center; border:1px solid;">SEMESTER '.$Smster.'</th></tr>';
	}
	
	
	$Row1.="
	<tr>
		<td style='text-align: center; border:1px solid;'>$no1</td>
		<td style='text-align: center; border:1px solid;'> ".($data['kat']==1?'*':"")."$data[kode_mk] </td>
		<td style='text-align: center; border:1px solid;'> $data[nama_mk] </td>
		<td style='text-align: center; border:1px solid;'> $data[sks] </td>
		<td style='text-align: center; border:1px solid;'> $data[nilai] </td>
	</tr>";
}

?>

<table width="100%" style="font-size:10px">
		<tr>
			<td rowspan="3" width="15%"><center><img src="<?= Url::to("@web/ypkp.png")?>" width="10%"></center></td>
			<td width="45%" valign="bottom">
				<h4><b>UNIVERSITAS SANGGA BUANA YPKP</b></h4>
			</td>
		</tr>
		<tr>
			<td>Jalan PHH Mustopa No. 68 - Bandung 40124</td>
		</tr>
		<tr>
			<td>Telp. 022-7202233 / Fax. 022-7201756</td>
			<td>Website : http://www.usbypkp.ac.id &nbsp; Email : admin@usbypkp.ac.id</td>
		</tr>
	</table>
<p style="border-style: solid; border-width: 3px; margin-bottom: 5px;"></p>
<p style="border-style: solid; border-width: 1px;"></p>

	<table width="100%">
		<tr>
			<td><u><center><b>TRANSKRIP NILAI AKADEMIK</b></center></u></td>
		</tr>
	</table>
<br />
	<table style="font-size:10px" width="100%">
		<tr>
			<td width="80px" style="padding-bottom: 5px;">Nama</td>
			<td style="padding-bottom: 5px;" width="2%">&nbsp;:&nbsp;</td>
			<td style="padding-bottom: 5px;"><?= $ModMhs->mhs->people->Nama;?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td width="80px" style="padding-bottom: 5px;">NPM</td>
			<td style="padding-bottom: 5px;">&nbsp;:&nbsp;</td>
			<td style="padding-bottom: 5px;"><?= $ModMhs->mhs_nim;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>&nbsp;:&nbsp;</td>
			<td><?= $ModMhs->mhs->people->alamat;?></td>

			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td>Program Studi</td>
			<td width="2%">&nbsp;:&nbsp;</td>
			<td><?= \app\models\Funct::JURUSAN()[$ModMhs->jr_id];?></td>
		</tr>
	</table>
	
		<table width="100%" style="font-size:10px; border:1px solid black;border-collapse:collapse;">
			<tr style="border-style: solid; border-width: 1px">
				<th style="text-align: center; border:1px solid;">NO</th>
				<th style="text-align: center; border:1px solid;">Kode</th>
				<th style="text-align: center; border:1px solid;">Matakuliah</th>
				<th style="text-align: center; border:1px solid;">SKS</th>
				<th style="text-align: center; border:1px solid;">Nilai</th>

				<th colspan="1" rowspan="100"></th>
				
				<th style="text-align: center; border:1px solid;">NO</th>
				<th style="text-align: center; border:1px solid;">Kode</th>
				<th style="text-align: center; border:1px solid;">Matakuliah</th>
				<th style="text-align: center; border:1px solid;">SKS</th>
				<th style="text-align: center; border:1px solid;">Nilai</th>
			</tr>
			<tr>
				<th colspan="5" style="border:1px solid;"><center>SEMESTER 1</center></th>
				<th colspan="5" style="border:1px solid;"><center>SEMESTER 7</center></th>
			</tr>
			<tr>
				<?= $Row1 ?>
			</tr>	
			
		</table>
		
		<br />
		<table width="100%" frame="box" style="font-size:10px">
			<tr>
				<th colspan="3" style="padding-bottom: 5px;">&nbsp;&nbsp;Rangkuman Nilai :</th>
			</tr>
			<tr>
				<th width="30%" style="padding-bottom: 5px;">&nbsp;&nbsp;Total SKS</th>
				<th width="2%">:</th>
				<th><?= $TotSks ?> SKS</th>
			</tr>
			<tr>
				<th style="padding-bottom: 5px;">&nbsp;&nbsp;Total Matakuliah</th>
				<th>:</th>
				<th>Tes</th>
			</tr>
			<tr>
				<th style="padding-bottom: 5px;">&nbsp;&nbsp;IPK</th>
				<th>:</th>
				<th><?= number_format(($SumSks/$TotSks),2)?></th>
			</tr>
		</table>

<br />
	<p style="border-style: solid black; border-width: 1px; margin-bottom: 5px;"></p>
	<p style="border-style: solid; border-width: 3px;"></p>