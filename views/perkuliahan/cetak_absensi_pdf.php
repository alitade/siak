<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: sans-serif;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body >

<div class="page" style="font-family: sans-serif;">
 <table width="100%"  border="0" class="" style="margin-bottom: 0px;margin-top: -25px;">  
  <tr>
    <td width="10%"><img style="margin-bottom: -5px; margin-top: -1px;" src="<?php echo Url::to('@web/ypkp.png'); ?>" width="80px"></td>
    <td width="100%">
      <div>
        <h4><b style ="font-size: 15pt;">UNIVERSITAS SANGGA BUANA YPKP</b></h4>
        <p style="margin-bottom: 8px;margin-top: -20px;font-size:15pt; "><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 </small></p>
        <p style="margin-bottom: 0px; margin-top: -12px;font-size:15pt; "><small>E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
      </div>
    </td>
  </tr>
</table>
<br> 
<?php 
	$waktu = trim(Funct::HARI()[date('w',strtotime($header['tanggal']))]) .', '.trim(Funct::TANGGAL($header['tanggal'])) . ' / '.trim(substr($header['jam'],0,5));
 ?>

<table width="100%" border="1" class="" cellpadding="0" cellspacing="0" style="margin-top:-15px">
<thead> 
 <tr>
   <th height="35" style="align-content: center; text-align: center; font-size: 20pt;"
    colspan="12" align="center" valign="middle" scope="row">DAFTAR HADIR <?=$jenis?></th>  
  </tr>
 	<tr>
 	<td width="250" height="40px" colspan="2" style="font-size:16pt; padding-left: 9px;"><b>Mata Kuliah</b></td><td  colspan="4" style="font-size:16pt; padding-left: 9px;"><?=$header['matakuliah']?></td>
 	<td colspan="3" style="font-size:16pt;  padding-left: 9px;"><b>Ruangan</b></td><td colspan="3" style="font-size:16pt; padding-left: 9px;"><?=$header['ruang']?></td></tr>
 	<tr><td width="187" height="40px" colspan="2" style="font-size:16pt;  padding-left: 9px;"><b>Nama Dosen</b></td><td  colspan="4" style="font-size:15pt; padding-left: 9px;"><?=$header['dosen']?></td>
 	<td colspan="3" style=" padding-left: 9px;font-size:16pt; "><b>Program</b></td><td colspan="3" style="font-size:14pt; padding-left: 9px;"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td></tr>
 	<tr><td width="187" height="40px" colspan="2" style=" padding-left: 9px;font-size:16pt; "><b>Hari, Tanggal / Jam</b></td><td  colspan="4" style="font-size:15pt; padding-left: 9px;"><?=trim($waktu);?></td>
 	<td colspan="3" style=" padding-left: 9px;font-size:16pt; "><b>Semester</b></td><td colspan="3" style="font-size:16pt; padding-left: 9px;"><?=$header['semester']?></td></tr>
  <tr>
    <th width="35" height="45px" style="font-size:15pt;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NO</th>
    <th width="120" style="font-size:15pt;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">NPM</th>
    <th width="250" style="font-size:15pt;align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NAMA MAHASISWA</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;width:50px;padding-left:4px;padding-right:4px;" align="center" valign="middle" nowrap="nowrap" scope="col">HADIR</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">%</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">Nilai Huruf</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;padding-left:4px;padding-right:4px;" align="center" valign="middle" nowrap="nowrap" scope="col">Total</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;width:50px;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS1</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;width:50px;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS2</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;width:50px;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">UTS</th>
    <th width="60" style="font-size:15pt;align-content: center; text-align: center;width:50px;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">UAS</th>
    <th width="50"  style="font-size:15pt;align-content: center; text-align: center;width:90px;padding-left:4px;padding-right:4px" align="center" valign="middle" nowrap="nowrap" scope="col">TANDA TANGAN</th>
  </tr>
</thead>

	<?php 
	$num = 1;
	foreach ($data as $m): ?>
		<?php
		$absen=$m['absen'];
                $sesi= empty(@$_GET['sesi']) ? 14 : @$_GET['sesi'];

		$persen =($m['absen']? number_format(($m['absen']/$sesi*100))."%":"0%");
		/*if($jenis =="UJIAN TENGAH SEMESTER"){
			$absen ="";
			$persen="";
		}*/
		$persen='100%';
		 if ($num % 21==0): ?>

			</table>
			</div>
			<?php if ($jenis=='UJIAN AKHIR SEMESTER'): ?>
				<div style="float: right; text-align: right; font-size: 13px; font-style: italic; font-weight: bold;">Note : Untuk Nama Mahasiswa yang tidak muncul pada absensi, silahkan hubungi Bagian Keuangan.</div>
			<?php endif ?>
			<br>
			</pagebreak>

			<pagebreak>
			<div class="page">
			<table width="100%" border="1" class="" cellpadding="0" cellspacing="0">
			<thead> 
			 <tr>
			   <th height="35" style="align-content: center; text-align: center; font-size: 20pt;"
			    colspan="12" align="center" valign="middle" scope="row">DAFTAR HADIR <?=$jenis?></th>  
			  </tr>
			 	<tr>
			 	<td width="255" height="45px" colspan="2" style="font-size:16pt; padding-left: 9px;"><b>Mata Kuliah</b></td><td  colspan="5" style="font-size:16pt; padding-left: 9px;"><?=$header['matakuliah']?></td>
			 	<td colspan="3" style="font-size:16pt;  padding-left: 9px;"><b>Ruangan</b></td><td colspan="3" style="font-size:16pt; padding-left: 9px;"><?=$header['ruang']?></td></tr>
			 	<tr><td width="187" height="45px" colspan="2" style="font-size:16pt;  padding-left: 9px;"><b>Nama Dosen</b></td><td  colspan="5" style="font-size:15pt; padding-left: 9px;"><?=$header['dosen']?></td>
			 	<td colspan="3" style=" padding-left: 9px;font-size:16pt; "><b>Program</b></td><td colspan="3" style="font-size:16pt; padding-left: 9px;"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td></tr>
			 	<tr><td width="187" height="40px" colspan="2" style=" padding-left: 9px;font-size:16pt; "><b>Hari, Tanggal / Jam</b></td><td  colspan="5" style="font-size:15pt; padding-left: 9px;"><?=trim($waktu);?></td>
			 	<td colspan="3" style=" padding-left: 9px;font-size:16pt; "><b>Semester</b></td><td colspan="3" style="font-size:16pt; padding-left: 9px;"><?=$header['semester']?></td></tr>
			  <tr>
			    <th width="35" height="45px" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NO</th>
			    <th width="150" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap" scope="col">NPM</th>
			    <th width="250" style="align-content: center; text-align: center;" align="center" valign="middle"  nowrap="nowrap"  scope="col">NAMA MAHASISWA</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">HADIR</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">%</th>
			    <th width="60" style="align-content: center; text-align: center;" align="center" valign="middle" nowrap="nowrap" scope="col">Nilai Huruf</th>
			    <th width="60" style="align-content: center; text-align: center;" align="center" valign="middle" nowrap="nowrap" scope="col">Total</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS1</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">TGS2</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">UTS</th>
			    <th width="60" style="align-content: center; text-align: center;width:50px" align="center" valign="middle" nowrap="nowrap" scope="col">UAS</th>
			    <th width="100"  style="align-content: center; text-align: center;width:150px" align="center" valign="middle" nowrap="nowrap" scope="col">TANDA TANGAN</th>
			  </tr>
			</thead>
		<?php endif ?>
		<tr >
	    <th style=" padding-left: 9px;font-size:18pt;padding-right:8px" align="center" height="56" valign="middle" nowrap="nowrap" scope="row"><?=$num?></th>
	    <td style=" padding-left: 8px;padding-right: 8px;font-size:18pt;width:100px"  nowrap="nowrap" align="left" valign="middle"><?=$m['mhs_nim']?></td>
	    <td style=" padding-left: 9px;padding-right: 8px;font-size:18pt;width:400px" align="left" valign="middle" ><?=$m['nama']?></td>
	    <td align="center" valign="middle" style="font-size:15pt;" ><?= $absen ?></td>
	    <td align="center" valign="middle" nowrap="nowrap" style="font-size:15pt;"><?= $persen ?></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td align="center" valign="middle" nowrap="nowrap"></td>
	    <td style=" padding-left:7px;font-size:12pt;" align="left" valign="bottom"><b><?=$num?></b></td>
	  </tr>
	<?php 
	$num ++;
	endforeach ?>
</table>
<pagebreak/>
<?php if ($jenis=='UJIAN AKHIR SEMESTER'): ?>
	<div style="float: right; text-align: right; font-size: 13px; font-style: italic; font-weight: bold;">Note : Untuk Nama Mahasiswa yang tidak muncul pada absensi, silahkan hubungi Bagian Keuangan.</div>
<?php endif ?>
<br>
 <table style="width: 100%;font-size: 13px;">
   <tr>
     <td style="width:200px;">NAMA DOSEN</td>
     <td>: ..........................................................</td>
   </tr>
   <tr style="width:300px;height: 40px;">
     <td >TANGGAL PENYERAHAN</td>
     <td>: ..........................................................</td>
     <td></td>
     <td style="font-size: 13px;">TANDA TANGAN</td>
     <td>[..........................................................]</td>
   </tr>
   <tr style="width:500px;height: 60px;"></tr>
 </table>

</div>

</div>
</body>

<script type="text/javascript">
  window.print();
  function printDiv(divName) {
     
 /*    var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     
     document.body.style.marginLeft = "158px";
     document.body.style.marginRight = "100px";
     document.body.style.marginTop = "0px";
     document.body.style.fontFamily = "Segoe UI";
     
     document.body.innerHTML = printContents;*/
   
    // document.body.innerHTML = originalContents;
    //  window.print();
}
</script>
 
</html>
