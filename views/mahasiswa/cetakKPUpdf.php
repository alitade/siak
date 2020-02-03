<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
use yii\grid\GridView;
?>


<p style="text-align:center;font-size:12px;text-transform:uppercase"><b>KARTU PESERTA UJIAN <?=(strtolower($jenis)=='uts'?'TENGAH SEMESTER':'AKHIR SEMESTER')."<br />SEMESTER $smt" ?></b></p>
<table width="100%" class="table">
	<tr valign="top">
        <td>
            <?= Html::img('@web/pt/'.Funct::profMhs(Yii::$app->user->identity->username,"pt"),['height'=>'100']); ?>
        </td>
        <td valign="top">
            <table>
                <tr><td>NIM </td><td>:</td><td><?php echo Yii::$app->user->identity->username; ?></td></tr>
                <tr><td>Nama</td><td>:</td><td><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td></tr>
                <tr><td>Jurusan </td><td>:</td><td><?php echo $jr->jr_jenjang."-".$jr->jr_nama; ?></td></tr>
                <tr><td>Program</td><td>:</td><td><?php echo $pr->pr_nama; ?></td></tr>
            </table>    
        </td>
    </tr>
</table>
<hr style="margin-top:6px;margin-bottom:6px"/>

<div class="col-sm-12">
<?php
	$row="";
	$n=0;
	foreach($dataProvider->getModels() as $d){
		$hari	= '-';//$data['jdwl_hari'];
		$masuk	= '-';//$data['jdwl_masuk'];
		$keluar	= '-';//$data['jdwl_keluar'];
		$jadwal = 'BELUM DI SET';
		if(strtolower($jenis)=="uas" && !empty($d['jdwl_uas']) ){
			$hari	= date('N',strtotime($d['uas']));
			if (empty($d['uas'])) {
			  $jadwal = 'BELUM DI SET';
			}else{
			   $masuk = $d['uas_masuk'];
			   $keluar = $d['uas_keluar'];
			   $jadwal = Funct::HARI()[$hari].', '.$masuk.' - '.$keluar;
			}
		}
	
		if(strtolower($jenis)=="uts" && !empty($d['jdwl_uts']) ){
            $hari	= date('N',strtotime($d['jdwl_uts']));
			$masuk	= date('H:m',strtotime($d['jdwl_uts']));
			$keluar	= date('H:m',strtotime($d['jdwl_uts_out']));
            $jadwal = Funct::HARI()[$hari].', '.$masuk.' - '.$keluar;
		}
		$n++;
		$row.="<tr>
			<td>$n</td>
			<td>$d[mtk_kode] - $d[mtk_nama]</td>
			<td>$d[ds_nm]</td>
			<td>$jadwal</td>
			<td></td>
		</tr>
		";
		
		//echo $d['ds_nm']." | ";
	}
?>
<table class='table table-condensed table-bordered' style="font-size:10px">
  <thead>
  <tr>
    <th>No</th>
    <th>Matakuliah</th>
    <th>Dosen</th>
    <th>Jadwal</th>
    <th>*Paraf</th>
  </tr>
  </thead>
  <tbody>
  <?= $row ?>
  </tbody>
</table>
<span style="font-size:10px">
*Paraf Oleh Pengawas
</span>
</div>