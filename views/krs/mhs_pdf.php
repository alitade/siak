<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.qrcode.min.js'); ?>"></script> 
<p style="text-align:center;font-size:14px;text-transform: uppercase"><b>Kartu Rencana Studi Semester <?= $model->kr->kr_nama ?></b></p>

<?php
    $linkImg=Url::to('@web/pt/no_foto.jpg');
    if(file_exists("../web/pt/".$model->mhs->dft->bio->photo) && $model->mhs->dft->bio->photo){$linkImg=Url::to("@web/pt/".$model->mhs->dft->bio->photo);}



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
    $tgl="-------";
    if($model->app==1){
        if($model->app_date){
            $tgl=explode(" ",$model->app_date);
            $tgl=Funct::TANGGAL($tgl[0]).", ".substr($tgl[1],0,5);
        }

    }
	if(!empty($_GET['kurikulum'])){$smt=$_GET['kurikulum'];}else{$smt="";}?>
        <table class='table table-condensed borderless'>
      	  <tr valign="top">
          	<td class="col-sm-1"><?= Html::img($linkImg,['height'=>'100']); ?></td>
            <td valign="top">
            	<table>
                    <tr>
                        <th> Nama </th><td> : </td><td> <?= $model->mhs->dft->bio->Nama ?> </td>
                    </tr>
                    <tr>
                        <th> NPM </th><td> : </td><td> <?php echo $model->nim; ?> </td>
                    </tr>
                    <tr>
                        <th> Program Studi / Fakultas </th>
                        <td> : </td>
                        <td> <?= $model->mhs->jr->jr_jenjang.' '.$model->mhs->jr->jr_nama ?> / <?= $model->mhs->jr->fk->fk_nama ?> </tr>
                    <tr>
                        <th> Dosen Wali </th><td> : </td><td> <?= $model->mhs->wali->ds_nm ?> </td>
                    </tr>
                </table>
            </td>
            <td><img src="<?php echo Url::to('@web/qr.php?a='.Yii::$app->user->identity->username.'-'.date('YmdHis')); ?>"></td>
          </tr>
      	</table>

<?php
$row =""; 
$no = 0;
$total = 0;
$status=[0=>'Ditunda','Disetujui','Ditolak'];
foreach($krs as $data):
	$jdwl=explode("|",$data['jadwal']);
	$jd="";
	$rg="";
	foreach($jdwl as $k=>$v){
		$Info=explode('#',$v);
		$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2] - $Info[3]";
		$rg.="$Info[4]<br />";
		$jd.=$ket."</div>";
	}
	$jdwl=$jd;
    $info=$data['krs_ulang']==1?"[U]":"[B]";
  $no++;
  $row.="<tr>
    <td> <b>$no</b> </td> 
    <td> $info ".$data['mtk_kode']." </td>
    <td> ".$data['mtk_nama']." </td>
    <td> ". $data['mtk_sks']." </td>
    <td> ".$data['jdwl_kls']." </td>
    <td> $jdwl </td>    
    <td>".$status[$data['krs_stat']]."</td>    
  </tr>";
  $total+=$data['mtk_sks'];
endforeach;
?>       
<table class='table table-condensed table-bordered'>
  <tr>
    <th>No</th>
    <th>Kode</th>
    <th>Nama Matakuliah</th>
    <th>SKS</th>
    <th>Kelas</th> 
    <th>Jadwal</th>
    <th>Status</th>
  </tr>
  <?=$row?>
</table>
<b>[B:Baru] [U:Mengulang]</b>
<table class="table table-bordered">
    <tr>
        <th width="30px"></th>
        <th><center>Teori</center></th>
        <th><center>Praktek</center></th>
        <th><center>Teori&amp;Praktek</center></th>
        <th><center>Total</center></th>
    </tr>
    <tr>
        <th><center>SKS</center></th>
        <td><center> </center></td>
        <td><center> </center></td>
        <td><center> </center></td>
        <td><center><b><?= $total?></b> SKS</center></td>
    </tr>
</table>
<span style="font-size: 12px">
    *Jika status jadwal di KRS masih ditunda, silahkan mengubungi dosen wali<br>
    *Jadwal KRS yang bisa diikuti / dilaksanakan mahasiswa adalah jadwal KRS yang berstatus <b>DISETUJUI</b>
</span>
<p></p>
<table border="0" width="100%">
    <tr>
        <td width="50%" style="font-size:9px"><b>Tanggal Persetujuan:<?= $tgl ?></b></td>
        <td width="50%" align="right"><?= '<i><b style="font-size:9px">Print:'.date('r').'</b></i>';?></td>
    </tr>
</table>

