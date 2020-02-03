<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.qrcode.min.js'); ?>"></script> 
<p style="text-align:center;font-size:14px"><b>Riwayat Pembayaran</b></p>
<br/>
<table class='table table-condensed borderless'>
<tr>
	<td>NPM</td><td>: <?= $mhs->mhs_nim; ?></td><td>Jurusan</td><td>: <?= $jr->jr_id."-".$jr->jr_nama;?></td>
</tr>
<tr>
	<td>Nama</td><td>: <?= Funct::profMhs($mhs->mhs_nim,"Nama");?></td><td>Program</td><td>: <?= Funct::getProgramKeuangan($mhs->mhs_nim); ?></td>
</tr>
</table>
<br/><br/>
<div class="row col-wrap">
<div class="col-md-6 col">
<u><b>Riwayat Pembayaran Rutin</b></u>
<font size="1">
<table class="table table-striped">
	<th>Tahun</th>
	<th>Total</th>
	<th>Sisa</th>
	<th>Status</th>
<?php
$ck="t";
foreach($pkrs as $row){
	if($ck!=$row['tahun']){
		?>
		<tr border="1">
			<td><b><?= $row['tahun']; ?></b></td>
			<td><b><?= number_format($row['total'],0,'.','.'); ?></b></td>
			<td><b><?php if($row['sisa']<=0){echo "0"; }else{ echo number_format($row['sisa'],0,'.','.'); }?></b></td>
			<td><b><?= $row['status']; ?></b></td>
		</tr>
		<tr>
			<td colspan="2"><?= $row['tanggal']; ?></td>
			<td colspan="2"><?= number_format($row['jumlah'],0,'.','.'); ?></td>
		</tr>
		<?php
	}else{ ?>
		<tr>
			<td colspan="2"><?= $row['tanggal']; ?></td>
			<td colspan="2"><?= number_format($row['jumlah'],0,'.','.'); ?></td>
		</tr>
	<?php } 
$ck = $row['tahun'];
}
?>
</table>
</font>
</div>
<div class="col-md-6 col">
<u><b>Riwayat Pembayaran Non Rutin</b></u>
<font size="1">
<table class="table table-striped">
	<th>Jenis</th>
	<th>Total</th>
	<th>Sisa</th>
	<th>Status</th>
<?php
$ckb = "t";
foreach($pbeban as $row){
	if($ckb!=$row['nama_beban']){
		?>
		<tr boder="1">
			<td><b><?= $row['nama_beban'];?></b></td>
			<td><b><?php if((!isset($row['total'])) || $row['total']<=0){ echo "0"; }else{ echo number_format($row['total'],0,'.','.'); } ?></b></td>
			<td><b><?php if($row['sisa']<=0){ echo "0"; }else{ echo number_format($row['sisa'],0,'.','.'); } ?></b></td>
			<td><b><?= $row['status']; ?></b></td>
		</tr>
		<tr>
			<td colspan="2"><?= $row['tanggal']; ?></td>
			<td colspan="2"><?= number_format($row['jumlah'],0,'.','.'); ?></td> 
		</tr>
		<?php
	}else{
	?>
		<tr>
			<td colspan="2"><?= $row['tanggal']; ?></td>
			<td colspan="2"><?= number_format($row['jumlah'],0,'.','.'); ?></td> 
		</tr>
	<?php
	}
	$ckb = $row['nama_beban'];
}
?>
</table>
</font>
</div>
<center>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
Bag. Keuangan USB YPKP
<br/>
<br/>
<br/>
<br/>
    ____________________
</center>
</div>
<!--<br/></br></br>
 <div class="row col-wrap">

    <div class="col-md-6 col">
      
    </div>

    <div class="col-md-6 col">
    <center>
    
    Bag. Keuangan USB YPKP
<br/>
<br/>
<br/>
<br/>
    ____________________
    
    </center>
    </div>

  </div>-->