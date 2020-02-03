<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<!DOCTYPE html>
<html>
<head>
<title>DAFTAR HADIR</title>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-width: 1px;padding-left: 0px;padding-right: 0px}
.tg td{font-family:sans-serif;font-size:14px;padding:2px 2px;border-style:solid;border-width: 2px;overflow:hidden;word-break:normal;}
.tg th{font-family:sans-serif;font-size:14px;font-weight:bold;padding:2px 2px;border-style:solid;overflow:hidden;word-break:normal;}
.tg tr th{font-family:sans-serif;font-size:12.5px;font-weight:bold;padding:1px 1px;border-style:solid;border-width: 2px;overflow:hidden;word-break:normal;}
.tg .td-head{vertical-align:center;font-size:14px;font-weight: bold;}
</style>
<style>
/*@page {  margin-top: 0cm;margin-left: 2cm;margin-bottom: 0cm;margin-right: 0cm;font-family: sans-serif; }*/
div.page { margin-top: 0cm;page-break-before: always } 
</style>
</head>
<body style="font-family: sans-serif;">


<div class="page" style="font-family: sans-serif;">
<table class="" style="margin-bottom: 0px;margin-top: -38px;" width="100%" border="0">  
  <tbody><tr>
    <td width="10%"><img style="margin-bottom: -17px; margin-top: 12px;" src="<?php echo Url::to('@web/ypkp.png'); ?>" width="60px"></td>
    <td width="100%">
      <div>
        <h4><b style="font-size: 16pt;">UNIVERSITAS SANGGA BUANA YPKP</b></h4>
        <p style="margin-bottom: 10px;margin-top: -25px;font-size:12pt; "><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 </small></p>
        <p style="margin-bottom: 0px; margin-top: -12px;font-size:12pt; "><small>E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
      </div>
    </td>
  </tr>
</tbody>
</table>
<?php 
    $waktu = trim(Funct::HARI()[date('w',strtotime($header['tanggal']))]) .', '.trim(Funct::TANGGAL($header['tanggal'])) . ' / '.trim(substr($header['jam'],0,5));
 ?>
 <table width="100%" class="tg" border="1" cellpadding="0" cellspacing="0" >
 <thead> 
  <tr>
    <th colspan="12" style="font-size:15px">DAFTAR HADIR <?=$jenis?></th>
  </tr>
  <tr>
    <td class="td-head" style="font-size:8.5pt" height="20" colspan="2">MATA KULIAH</td>
    <td colspan="4" style="font-size:8pt"><?=$header['matakuliah']?></td>
    <td class="td-head" style="align-content: center;font-size:9pt" colspan="2">RUANGAN</td>
    <td colspan="4" style="font-size:8pt"><?=$header['ruang']?></td>
  </tr>
  <tr>
    <td class="td-head" style="align-content: center;font-size:9pt" height="20" colspan="2">NAMA DOSEN</td>
    <td colspan="4" style="font-size:8pt"><?=$header['dosen']?></td>
    <td class="td-head" colspan="2" height="20" style="font-size:9pt">PROGRAM</td>
    <td colspan="4" style="font-size:8pt"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td>
  </tr>
  <tr>
    <td class="td-head" colspan="2" height="20" style="font-size:8.5pt">HARI, TANGGAL / JAM</td>
    <td colspan="4" style="font-size:8pt"><?=trim($waktu);?></td>
    <td class="td-head" colspan="2" style="font-size:9pt">SEMESTER</td>
    <td colspan="4" style="font-size:8pt"><?=$header['semester']?></td>
  </tr>
  <tr style="align-content: center; text-align: center;" >
    <th width="25" style="font-size:8pt">NO</th>
    <th width="75" style="font-size:8pt">NPM</th>
    <th width="190" style="font-size:8pt">NAMA MAHASISWA</th>
    <th width="15" style="font-size:8pt">HADIR</th>
    <th width="25" style="font-size:8pt">%</th>
    <th width="30" style="font-size:8pt">NILAI HURUF</th>
    <th width="30" style="font-size:8pt">TOTAL</th>
    <th width="25" style="font-size:8pt">TGS1</th>
    <th width="25" style="font-size:8pt">TGS2</th>
    <th width="30" style="font-size:8pt">UTS</th>
    <th width="30" style="font-size:8pt">UAS</th>
    <th width="80" style="font-size:8pt">TANDA TANGAN</th>
  </tr>
  </thead>

  <!-- KODING -->

   <?php 
    $num = 1;
    foreach ($data as $m): ?>
    <?php 
        $absen=$m['absen'];
        $sesi= empty(@$_GET['sesi']) ? 14 : @$_GET['sesi'];
        $persen =($m['absen']? number_format(($m['absen']/$sesi*100))."%":"0%");
        $persen =number_format($m['persen'],2).'%';
    
        /*if($jenis =="UJIAN TENGAH SEMESTER"){
            $absen ="";
            $persen="";
        }*/
         if ($num % 21==0): ?>
            </table>
            </div>
            <?php if ($jenis=='UJIAN AKHIR SEMESTER'): ?>
                <div style="float: right; text-align: right; font-size: 13px; font-style: italic; font-weight: bold;">Note : Untuk Nama Mahasiswa yang tidak muncul pada absensi, silahkan hubungi Bagian Keuangan.</div>
            <?php endif ?>
            <br>
            <div class="page">
            <table width="100%" class="tg" border="1" cellpadding="0" cellspacing="0" style="margin-top:15px" >
            <thead> 
			  <tr>
			    <th colspan="12" style="font-size:15px">DAFTAR HADIR <?=$jenis?></th>
			  </tr>
			  <tr>
			    <td class="td-head" style="font-size:8.5pt" height="20" colspan="2">MATA KULIAH</td>
			    <td colspan="4" style="font-size:8pt"><?=$header['matakuliah']?></td>
			    <td class="td-head" style="align-content: center;font-size:9pt" colspan="2">RUANGAN</td>
			    <td colspan="4" style="font-size:8pt"><?=$header['ruang']?></td>
			  </tr>
			  <tr>
			    <td class="td-head" style="align-content: center;font-size:9pt" height="20" colspan="2">NAMA DOSEN</td>
			    <td colspan="4" style="font-size:8pt"><?=$header['dosen']?></td>
			    <td class="td-head" colspan="2" height="20" style="font-size:9pt">PROGRAM</td>
			    <td colspan="4" style="font-size:8pt"><?=$header['kode'].":".$header['program']." (".$header['kelas'].") "?></td>
			  </tr>
			  <tr>
			    <td class="td-head" colspan="2" height="20" style="font-size:8.5pt">HARI, TANGGAL / JAM</td>
			    <td colspan="4" style="font-size:8pt"><?=trim($waktu);?></td>
			    <td class="td-head" colspan="2" style="font-size:9pt">SEMESTER</td>
			    <td colspan="4" style="font-size:8pt"><?=$header['semester']?></td>
			  </tr>
			  <tr style="align-content: center; text-align: center;" >
			    <th width="25" style="font-size:8pt">NO</th>
			    <th width="75" style="font-size:8pt">NPM</th>
			    <th width="190" style="font-size:8pt">NAMA MAHASISWA</th>
			    <th width="15" style="font-size:8pt">HADIR</th>
			    <th width="25" style="font-size:8pt">%</th>
			    <th width="30" style="font-size:8pt">NILAI HURUF</th>
			    <th width="30" style="font-size:8pt">TOTAL</th>
			    <th width="25" style="font-size:8pt">TGS1</th>
			    <th width="25" style="font-size:8pt">TGS2</th>
			    <th width="30" style="font-size:8pt">UTS</th>
			    <th width="30" style="font-size:8pt">UAS</th>
			    <th width="80" style="font-size:8pt">TANDA TANGAN</th>
			  </tr>
			  </thead>

        <?php endif ?>


    <tr>
        <td height="28" style="align-content: center; text-align: center;"><?=$num?></td>
        <td style="font-size:9pt" ><?=$m['mhs_nim']?></td>
        <td style="font-size:9pt" ><?=$m['nama']?></td>
        <td style="text-align:center;font-size:9pt"><?=$absen?></td>
        <td style="text-align:center;font-size:8pt"><?=$persen?></td>
        <td class=""></td>
        <td class=""></td>
        <td class=""></td>
        <td class=""></td>
        <td class=""></td>
        <td class=""></td>
        <td style=" padding-left:2px;font-size:7pt;" align="left" valign="bottom"><b><?=$num?></b></td>
    </tr>
      
  <?php $num ++; endforeach ?>
<!-- KODING -->
</table>

<?php if ($jenis=='UJIAN AKHIR SEMESTER'): ?>
    <div style="float: right; text-align: right; font-size: 12px; font-style: italic; font-weight: bold;">Catatan : Untuk Nama Mahasiswa yang tidak muncul pada absensi, silahkan hubungi Bagian Keuangan.
    </div>
    <br>
<?php else: ?>
	<hr>	
<?php endif ?>
 <table style="width: 100%;">
   <tr>
     <td style="width:180px;font-size: 10pt;">NAMA DOSEN</td>
     <td>: ..........................................</td>
   </tr>
   <tr style="width:300px;height: 40px;font-size: 10pt;">
     <td style="font-size: 10pt;">TANGGAL PENYERAHAN</td>
     <td style="font-size: 10pt;">: ...............................................</td>
     <td></td>
     <td style="font-size: 10pt;">TANDA TANGAN</td>
     <td style="font-size: 10pt;">[...............................................]</td>
   </tr>
   <tr style="width:500px;height: 60px;"></tr>
 </table>

</div>

</div>
</body>

<script type="text/javascript">
  window.print();
  function printDiv(divName) {
}
</script>
 
</html>
