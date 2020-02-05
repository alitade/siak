<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

use kartik\tabs\TabsX;

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Info',
        'content'=>'
			<div style="font-weight:bold">
			Aturan Absensi <i>Fingerprint</i>
			<ul>
				<li>Mahasiswa bisa melakukan absen masuk jika Dosen sudah melakukan absen masuk (Membuka Perkuliahan)</li>
				<li>Mahasiswa bisa melakukan absen keluar jika Dosen sudah melakukan absen keluar (Menutup Perkuliahan)</li>
				<li>Mahasiswa dianggap mengikuti perkuliahan jika sudah melakukan absen masuk dan absen keluar perkuliahan, serta tidak terlambat absen masuk perkuliahan</li>
				<li>Mahasiswa bisa malakukan absen masuk perkuliahan selanjutnya jika dosen sebelumnya telah menutup perkuliahan </li>
				<!-- 
				<li>Jika sampai +10 menit dari jadwal keluar perkuliahan dosen belum menutup perkuliahan, maka mahasiswa bisa melakukan absen masuk perkuliahan selanjutnya</li>
				-->
			</ul>
			</div>
		
		',
		'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Perkuliahan',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/perkuliahan'])],

    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Kehadiran',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/hadir-mhs2'])],
    ],
];
?>
<br>
<div>
<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_LEFT,
    'encodeLabels'=>false,
	//'sideways'=>TabsX::POS_LEFT,
	
]);
?>
</div>
<div style="clear:both"></div>
