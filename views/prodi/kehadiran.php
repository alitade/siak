<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use app\models\Funct;
use kartik\builder\Form;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Kehadiran Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>	
<div class="angge-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); 
	//echo \app\models\Funct::Hadir('86277');
	
	?>
    <?= 
	Form::widget([
    'formName' =>'Thn',
    'form' => $form,
    'columns' => 2,
    'attributes' => [
		'thn'=>[
			'label'=>'Tahun Akademik',
			'options'=>['placeholder'=>'...'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' =>app\models\Funct::AKADEMIK(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'mode'=>[
			'label'=>'Mode',
			'options'=>['placeholder'=>'...'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' =>[
					'0'=>'Sesi',
					'1'=>'Waktu',
				],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 

    ]


    ]);


	 ?>
    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['prodi/kehadiran-dosen'],['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<br /><br />

    <?php 
	if($KrKd>0){
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		//print_r($QueKuliah);
		echo '<div class="col-xl-12" style="overflow:auto;height:500px">
		Ket :
			<div class="label " style="background:green;"> Data dari mesin <i>fingerprint</i></div>
			<div class="label " style="background:blue;"> Rekap dokumen manual absensi</div>
			<div class="label " style="background:red;"> Tidak Ada Perkuliahan</div>
		<br />
		T* 		= Target Pertemuan Dalam 1 Semester<br />
		P* 		= Total Pertemuan Perkuliahan Dalam 1 Semester<br />
		%P* 	= P*/T*<br />
		%Mhs.	= AVG( %Kehadiran Mahasiswa / P*)

		
		'.
		
		$data.'
		<div style="width:100%;">'.
		Html::a('Cetak Pdf', ['prodi/cetak-kehadiran-dosen','kr'=>$Qthn['kr_kode']],['target'=>'blank_','class' => 'btn btn-primary']).'
		<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="1%" rowspan="2">No</th>
				<th width="100" rowspan="2">Jadwal</th>
				<th rowspan="2">Dosen</th>
				<th rowspan="2">Matakuliah</th>
				<th rowspan="2">&sum;Mhs.</th>
				<th colspan="16">SESI</th>
				<th colspan="2">%</th></tr><tr>
				';
		for($i=1;$i<=14;$i++){echo'<th width="1%">'.$i.'</th>';}
				

		echo'<th>T*</th> <th>P*</th><th>P*</th><th>Mhs.</th></tr></thead>';
		$TP=0;
		$TM=0;
		foreach($QueKuliah as $k=>$v){
			$n++;
			$fid=$v['fid'];
			echo"<tr>
				<td>$n</td>
				<td>".Funct::HARI()[$v['jdwl_hari']]."<br/>$v[jdwl_masuk]-$v[jdwl_keluar]</td>
				<td>$v[ds_nm]</td>
				<td>$v[mtk_kode]: $v[mtk_nama] ($v[jdwl_kls])</td>
			";
			
			$TotP=0;		
			$TotM=0;
			$Tot=0;
			for($i=1;$i<=14;$i++){
				#   0        1       2     3        4      5      6   7     8
				#DsMasuk|DsKeluar|Menit|DsGetFid|DsStat|TotMhs|Total|Tsesi|tpr 
				$d=explode('|',$v[$i]);
				if($i==1){echo"<td>$d[5]</td>";}
				
				if($d[4]==='1'){
					if($TotP < $d[7]){
						$TotP++;
						$TotM+=$d[6];
						$Tot+=$d[5];
					}
				}
				echo'
				<td > 
				<div style="background:'.($d[4]==='0' || !$d[4]?'red;':($d[4]==='1'?($d[3]==0 || empty($d[3])?'blue;':'green;'):'red')).'border:inset 1px #000;color:#fff;text-align:center">&nbsp;<b>'.
				($d[4]==='0'?0:$d[6])
				."</b></div><br /></td>";
			}
				$TP+=( round((($TotP>$d[7]?$d[7]:$TotP)/$d[7])*100));
				$TM+=round($TotM/$Tot*100);

			echo "
				<td>$d[7]</td>
				<td>$TotP</td>
				<td>".( round((($TotP>$d[7]?$d[7]:$TotP)/$d[7])*100))."</td>
				<td>".round($TotM/$Tot*100)." </td>";
					
			echo"</tr>";
		}
		echo'
		<tr><th colspan="21"><span style="float:right"> Total %</span></th><th>'.round($TP/$n).'</th><th>'.round($TM/$n).'</th></tr>
		</table></div></div>';
	}
	?>

</div>
