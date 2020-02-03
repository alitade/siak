<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

?>
<link href="<?= URL::to('@web/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.min.js'); ?>"></script> 
<script type="text/javascript" src="<?= Url::to('@web/js/jquery.qrcode.min.js'); ?>"></script> 
<p style="text-align:center;font-size:14px"><b>Kartu Rencana Studi <?= $kln->kr->kr_nama ?></b></p>

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
                        <td><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama"); ?></td>
                    </tr>
                    <tr>
                        <td>NPM</td>
                        <td>:</td>
                        <td><?php echo Yii::$app->user->identity->username; ?></td>
                        <!--td rowspan="4"><center><?//= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?><center></td>-->
                      </tr>
                      <tr>
                        <td>Jurusan / Fakultas</td>
                        <td>:</td>
                        <td><?php echo "$jr->jr_jenjang ".$jr->jr_nama." / ".Funct::Fakultas($jr->fk_id); ?></td>
                      </tr>
                </table>            
            </td>
            <td>
	            <img src="<?php echo Url::to('@web/qr.php?a='.Yii::$app->user->identity->username.'-'.date('YmdHis')); ?>">
            </td>
          </tr>
      	</table>

<?php
$row =""; 
$no = 0;
$total = 0;
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
    <td>$jdwl</td>    
    <td>$rg</td>    
  </tr>
  ";
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
    <th>Ruang</th>        
  </tr>
  <?=$row?>
</table>
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
<?= '<i><b style="font-size:9px">*'.date('r').'</b></i>';?>
