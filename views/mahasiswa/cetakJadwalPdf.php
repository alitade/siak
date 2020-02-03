<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
use yii\grid\GridView;
?>


<p style="text-align:center;font-size:12px;text-transform:uppercase"><b>JADWAL PERKULIAHAN <?= "SEMESTER $smt" ?></b></p>
<table width="100%" class="table">
	<tr valign="top">
        <td>
            <table>
                <tr><td>NIM </td><td>:</td><td><?php echo Yii::$app->user->identity->username; ?></td></tr>
                <tr><td>Nama</td><td>:</td><td><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td></tr>
            </table>
        </td>
        <td valign="top">
            <table>
                <tr><td>Program Studi </td><td>:</td><td><?php echo $jr->jr_jenjang."-".$jr->jr_nama; ?></td></tr>
                <tr><td>Program Perkuliahan</td><td>:</td><td><?php echo $pr->pr_nama; ?></td></tr>
            </table>    
        </td>
    </tr>
</table>
<div class="col-sm-12">
<?php
	$row="";
	$n=0;
	foreach($dataProvider->getModels() as $d){
		$hari	= '-';//$data['jdwl_hari'];
		$masuk	= '-';//$data['jdwl_masuk'];
		$keluar	= '-';//$data['jdwl_keluar'];

        $masuk	= substr($d['jdwl_masuk'],0,5);
        $keluar	= substr($d['jdwl_keluar'],0,5);
        $jadwal = Funct::HARI()[$d['jdwl_hari']].', '.$masuk.' - '.$keluar;
		$n++;
		$row.="<tr>
			<td>$n</td>
			<td>$d[mtk_kode] - $d[mtk_nama]</td>
			<td>$d[ds_nm]</td>
			<td>$jadwal</td>
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

  </tr>
  </thead>
  <tbody>
  <?= $row ?>
  </tbody>
</table>
</div>