	<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
use doamigos\qrcode\formats\MailTo;
use dosamigos\qrcode\QrCode;

?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/css/bootstrap.min.js'); ?>"></script> 
<style type="text/css">
body{
    font-size: 12px;
}
.navbar-fixed-bottom {
  display: none;
}
#footer{
  display: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
    display: none;
}

.borderless tbody tr td, .borderless tbody tr th, .borderless thead tr th {
    border: none;
}

link {
  display: none;
}

#page {
  padding-top: 0px;
}

@media print {
.header, .hide { visibility: hidden }
}

.grid-view {
  padding-top: 0px; 
}

a #top{display:none;}
</style>
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
  
	
?>
<br>
<table class="table table-condesed borderless">
	<tr>
		<td width="20%"><img src="<?php echo Url::to('@web/ypkp.png'); ?>" width="70%"></td>
		<td>&nbsp;</td>
		<td width="47%">
			<center>
				<b>UNIVERSITAS SANGGA BUANA YPKP</b>
				<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
				&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
			</center>
		</td>
		<td>	
			<center>
			 	<img src="<?php echo Url::to(['mahasiswa/qr']); ?>" width="50%">
			</center>
		</td>
	</tr>

</table>

<?php if(!empty($_GET['kurikulum'])){$smt=$_GET['kurikulum'];}else{$smt="";}?>
  	Tanggal Cetak : <?= $hari . "," . $tanggal . " ". $bulan . " ". $tahun; ?>
        <table class='table table-condensed borderless'>
      	  <tr>
          	<td rowspan="3" class="col-sm-1">
            	<?= Html::img('@web/pt/'.Funct::profMhs(Yii::$app->user->identity->username,"pt"),['height'=>'100']); ?>
            </td>
            <td width="20%">NPM</td>
            <td>:</td>
            <td><?php echo Yii::$app->user->identity->username; ?></td>
            <!--td rowspan="4"><center><?//= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?><center></td>-->
          </tr>
          <tr>
            <td>Nama</td>
            <td>:</td>
            <td><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama"); ?></td>
          </tr>
          <tr>
            <td>Jurusan / Fakultas</td>
            <td>:</td>
            <td><?php echo "$jr->jr_jenjang ".$jr->jr_nama." / ".Funct::Fakultas($jr->fk_id); ?></td>
          </tr>
          <!--tr>
            <td>Semester</td>
            <td>:</td>
            <td><//?php echo Funct::Kurikulum($smt); ?></td>
          </tr>-->    
      	</table>

<?php
$row =""; 
$no = 0;
$total = 0;
foreach($krs as $data):
  $no++;
  $row.="
  <tr>
    <td><b>$no</b></td>
    <td>".$data['mtk_kode']."</td>
    <td>".$data['mtk_nama']."</td>
    <td>".
	$data['mtk_sks']
	//$data['sks_']
	."</td>
    <td>".$data['jdwl_kls']."</td>
    <td>".Funct::getHari()[$data['jdwl_hari']].",".$data["jdwl_masuk"]." - ".$data['jdwl_keluar']."</td>    
    <td>".Funct::rgNama($data['rg_kode'])."</td>    
  </tr>
  ";
  $total+=$data['mtk_sks'];
endforeach;
?>       

<center><h4>Kartu Rencana Studi</h4></center>
&nbsp;
<table class='table table-condensed table-bordered'>
  <tr>
    <th>No</th>
    <th>Kode</th>
    <th>Nama Matakuliah</th>
    <th>SKS</th>
    <th>Kelas</th> 
    <th>Jadwal</th>
    <th>Ruang</th>        
  </tr>
  <?=$row?>
</table>

<table class="table-bordered">
	<tr>
		<th></th>
		<th width="100px"><center>Jumlah</center></th>
	</tr>
	<tr>
		<th width="200px">&nbsp; SKS Teori </th>
		<td><center> </center></td>
	</tr>
	<tr>
		<th>&nbsp; SKS Praktek </th>
		<td><center> </center></td>
	</tr>
	<tr>
		<th>&nbsp; SKS Teori + Praktek </th>
		<td><center> </center></td>
	</tr>
	<tr>
		<th>&nbsp; Total SKS</th>
		<td><center><b><?= $total?></b> SKS</center></td>
	</tr>
</table>





<script>
$(document).ready(function(){   
       var total = 0;
        $('td.rowDataSd').each(function() {
           total = total + parseInt($(this).text());
        }); 
		$('#sks').html(total);
	
});
</script>

<script type="text/javascript">
    window.print();

</script>