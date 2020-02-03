<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;



?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.qrcode.min.js'); ?>"></script> 
<p style="text-align:center;font-size:14px"><b>Absensi Mahasiswa Khusus <?= $kln->kr->kr_nama ?></b></p>

<?php
	$array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat", "Sabtu","Minggu");
	$hari = $array_hari[date("N")];
	//Format Tanggal
	$tanggal = date ("j");
	//Array Bulan
	$array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
	$bulan = $array_bulan[date("n")];
	//Format Tahun
	$tahun = date("Y");
	//Menampilkan hari dan tanggal

	$row =""; 
	$no = 0;
	$total = 0;
	foreach($krs as $data):
	  $no++;
	  $row.="
	  <tr>
		<td>".$data['Hari']."<br />".$data['jadwal']."</td>
		<td>".$data['dosen']."</td>
		<td>".$data['kode']."<br />".$data['matakuliah']."</td>
		<td> </td>
		<td> </td>
		<td> </td>
		<td> </td>
	  </tr>
	  ";
	  $total+=$data['mtk_sks'];
	endforeach;

  
	if(!empty($_GET['kurikulum'])){$smt=$_GET['kurikulum'];}else{$smt="";}?>
        <table class='table table-condensed borderless'>
      	  <tr valign="top">
          	<td class="col-sm-1">
            	<?= Html::img('@web/pt/'.Funct::profMhs(Yii::$app->user->identity->username,"pt"),['height'=>'100']); ?>
            </td>
            <td valign="top">
            	<table>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><?= Funct::profMhs($mhs->mhs_nim,"Nama"); ?></td>
                    </tr>
                    <tr>
                        <td>NPM</td>
                        <td>:</td>
                        <td><?= $mhs->mhs_nim ?></td>
                        <!--td rowspan="4"><center><?//= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?><center></td>-->
                      </tr>
                      <tr>
                        <td>Jurusan / Fakultas</td>
                        <td>:</td>
                        <td><?= $mhs->jr->jr_jenjang.' '.$mhs->jr->jr_nama." / ".Funct::Fakultas($mhs->jr->fk_id); ?></td>
                      </tr>
                </table>            
            </td>
            <td>
	            <img src="<?= Url::to('@web/qr.php?a='.$mhs->mhs_nim.'-'.date('YmdHis')); ?>">
            </td>
          </tr>
      	</table>

<i>* Harap kembalikan dokumen ini ke piket jika seluruh perkuliahan telah selesai, agar data absen bisa segera diproses<br /></i>
<table class='table table-condensed table-bordered'>
  <tr>
    <th width="75px" rowspan="2">Jadwal</th>
    <th rowspan="2">Dosen</th>
    <th rowspan="2">Matakuliah</th>
    <th colspan="3">Paraf Dosen</th>
  </tr>
  <tr>
    <?php
	for($a=$s;$a<=($s+3);$a++){echo"<th width='65px'>Sesi $a</th>";}
	?>
  </tr>
  <?=$row?>
</table>

<?= ""//'<div style="text-align:right"><i><b style="font-size:9px;">*'.date('r').'</b></i></div>';?>

<table style="font-weight:bold;text-align:right" align="right">
	<tr><td>
    	<?= "Bandung, ____________________ 2016"?><br />
        Direktur Sistem Informasi & Multimedia
        
    </td></tr>
	<tr><td height="70px">&nbsp;</td></tr>
	<tr><td align="center">Ir. Rudy Gunawan, MT</td></tr>
</table>
