<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
$num = 0;
$limit = 20;
$totalPages = ceil( count($data) / $limit ); 
?>
<div style="font-family:sans-serif !important">
  

 <table width="100%"  border="0" class="" style="margin-bottom: 0px;margin-top: 20px;">  
  <tr>
    <td width="15%"><img style="margin-bottom: -1px; margin-top: 0px;" src="<?php echo Url::to('@web/ypkp.png'); ?>" width="80px"></td>
    <td width="100%">
      <div>
        <h4><b style ="font-size: 20pt;">UNIVERSITAS SANGGA BUANA YPKP</b></h4>
        <p style="margin-bottom: 10px;margin-top: -20px;font-size:15pt; "><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 </small></p>
        <p style="margin-bottom: 0px; margin-top: -12px;font-size:15pt; "><small>E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
      </div>
    </td>
  </tr>
</table>
<!-- <hr style="color:black;margin-top:-0.3px;padding-bottom: -40px"> -->
  
  <?php $jum=1 ?>

  <?php for($i = 1; $i <= $totalPages; $i++) : ?>
  		
  		<?php $tmp = Funct::pagingArray($data,$i,$limit) ?>
  		<?php if ($i > 1): ?>
  			<br>
  		<?php endif ?>
  		<?php include 'cetak_absensi_thead.php' ?>
  		<?php foreach ($tmp as $d): ?>
  			<?php 
  				$absen=$d['absen'];
				$persen =($d['absen']? number_format(($d['absen']/12*100))."%":"0%");
				if($jenis =="UJIAN TENGAH SEMESTER"){
					$absen ="";
					$persen="";
				}
  			 ?>
  			 <tr>
			    <td align="right" style="height: 45px;width: 30px;padding-right: 7px;font-family: sans-serif;"><b><?=$jum?></b></td>
			    <td style="padding-left: 5px;padding-right: 2px;width: 135px;font-family: sans-serif"><?=$d['mhs_nim']?></td>
			    <td style="width: 200px;padding-left: 5px;font-family: sans-serif"><?=$d['nama']?></td>
			    <td style="width: 50px;font-family: sans-serif" align="center"><?=$absen?></td>
			    <td style="width: 50px;font-family: sans-serif" align="center"><?=$persen?></td>
			    <td style="width: 50px"></td>
			    <td style="width: 50px"></td>
			    <td style="width: 50px"></td>
			    <td style="width: 50px"></td>
			    <td style="width: 50px"></td>
			    <td style="width: 50px"></td>
			   	<td style="width: 100px;padding-left: 7px;font-family: sans-serif" align="left" valign="bottom" colspan="2"><b><?=$jum?></b></td>
			 </tr>
			 <?php $jum++ ?>
  		<?php endforeach ?>
  		</table>
      <div style="font-size: 10; font-family: sans-serif;">
        Halaman <?=$i?>
      </div>
  		<?php if ($jenis=='UJIAN AKHIR SEMESTER'): ?>
		    <div style="padding-top: -12px; float: right; text-align: right; font-size: 10px; font-style: italic; font-family: sans-serif ; font-weight: bold;">Note : Untuk Nama Mahasiswa yang tidak muncul pada absensi, silahkan hubungi Bagian Keuangan.</div>
		<?php endif ?>

  		<?php if ($i != $totalPages): ?>
  			<div class="page-break"></div>	
  		<?php endif ?>
        
  <?php endfor; ?>

<br>
 <table>
   <tr>
     <td style="width:170px;">NAMA DOSEN</td><td>: .............................................</td>
   </tr>
   <tr style="width:170px;height: 40px;">
     <td >TANGGAL PENYERAHAN</td><td>: .............................................</td>
     <td></td>
     <td >TANDA TANGAN</td><td>[.................................................]</td>
   </tr>
     <tr style="width:500px;height: 60px;"></tr>
 </table>

 </div>