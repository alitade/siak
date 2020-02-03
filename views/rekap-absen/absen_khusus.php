<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;



?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.qrcode.min.js'); ?>"></script> 

<p style="text-align:center;font-size:14px"><b>Absensi Dosen Khusus <?= $kln->kr->kr_nama ?></b></p>
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
	$thn="";
	$masa="";
	foreach($krs as $data):
	  $thn	= $data['thn'];
	  //$masa	= $data['thn'];
	  $no++;
	  $row.="
	  <tr>
		<td>".$data['Hari']."<br />".$data['jadwal']."</td>
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
          	<!--
          	<td class="col-sm-1">
            	<?= " "//Html::img('@web/pt/'.$,['height'=>'100']); ?>
            </td>
            -->
            <td valign="top">
            	<table>
                    <tr>
                        <th>Tahun Akademik</th>
                        <td>:</td>
                        <td><?= $thn; ?></td>
                    </tr>
                    <tr>
                        <th>Nama Dosen</th>
                        <td>:</td>
                        <td><?= $ds->ds_nm; ?></td>
                    </tr>
                </table>            
            </td>
          </tr>
      	</table>

<i>* Harap kembalikan dokumen ini ke piket jika seluruh perkuliahan telah selesai, agar data absen bisa segera diproses<br /></i>
<table class='table table-condensed table-bordered'>
  <tr style="text-align:center">
    <th width="75px" rowspan="2"><center>Jadwal</center></th>
    <th rowspan="2"><center>Matakuliah</center></th>
    <th colspan="4"><center>Paraf Dosen</center></th>
  </tr>
  <tr>
    <?php
	for($a=$s;$a<=($s+3);$a++){echo"<th width='65px'>Sesi $a</th>";}
	?>
  </tr>
  <?=$row?>
</table>

<?= ""//'<div style="text-align:right"><i><b style="font-size:9px;">*'.date('r').'</b></i></div>';?>


<table width="100%">
	<tr>
    	<td align="left">
            <table style="font-weight:bold;text-align:right" align="left">
                <tr><td>
                    <?= "Bandung, ____________________ 2016"?><br />
                    Direktur Sistem Informasi & Multimedia
                </td></tr>
                <tr><td height="70px">&nbsp;</td></tr>
                <tr><td align="center">Renol Burjulius, ST., M.Kom</td></tr>
            </table>
        </td>
        <td align="right">
            <table style="font-weight:bold;text-align:center" align="right">
                <tr><td>
                    <?= "Bandung, ____________________ 2016"?><br />
                    Dosen Pengajar
                </td></tr>
                <tr><td height="70px">&nbsp;</td></tr>
                <tr><td align="center"><?= $ds->ds_nm ?></td></tr>
            </table>
        </td>
    </tr>
</table>


