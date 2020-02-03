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
<div class="panel panel-primary">
<div class="panel-heading"><h4 class="panel-title"><?= Html::encode($this->title) ?></h4></div>	
	<div class="panel-body">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); 
	//echo \app\models\Funct::Hadir('86277');
	
	?>
    <?= 
	Form::widget([
    'formName' =>'Thn',
    'form' => $form,
    'columns' => 3,
    'attributes' => [
		'jr'=>[
			'label'=>'Jurusan',
			'options'=>['placeholder'=>'...'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' =>app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 

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
	<br />
	<div class="panel-body">
    <?php 
	if($KrKd>0){
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		//print_r($Gthn);
		echo '<div class="col-xl-12" style="overflow:auto;height:500px">
		Ket :
			<div class="label " style="background:green;"> Data dari mesin <i>fingerprint</i></div>
			<div class="label " style="background:blue;"> Rekap dokumen manual absensi</div>
			<div class="label " style="background:red;"> Tidak Ada Perkuliahan</div>
		<br />
		T* 		= Target Pertemuan Dalam 1 Semester<br />
		P* 		= Total Pertemuan Perkuliahan Dalam 1 Semester<br />
		%P* 	= P*/T*<br />
		%Mhs.	= AVG( %Kehadiran Mahasiswa / P*)'.

		$data.'
		<div style="width:100%;">'.
		Html::a('Cetak Pdf', ['dirit/cetak-kehadiran-dosen','kr'=>$Gthn['thn'],'jr'=>$Gthn['jr']],['target'=>'blank_','class' => 'btn btn-primary']).'
		<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="1%" rowspan="2">No</th>
				<th rowspan="2">Matakuliah</th>
				<th rowspan="2"><i class="fa fa-users"></i></th>
				<th colspan="16">SESI</th>
				<th colspan="2">%</th></tr><tr>
				';
		for($i=1;$i<=14;$i++){echo'<th width="1%">'.$i.'</th>';}
				

		echo'<th>T*</th> <th>P*</th><th>P*</th><th>Mhs.</th></tr></thead>';
		$TP=0;
		$TM=0;
        $HeadDs="";
		foreach($QueKuliah as $k=>$v){
            if($HeadDs!=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)" ){
                $HeadDs=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)";
                echo"<tr><th colspan='21'>$HeadDs</th></tr>";
            }
			$n++;
			$fid=$v['fid'];
			echo"<tr>
				<td>$n</td>
				<td>$v[mtk_kode]: $v[mtk_nama] ($v[jdwl_kls])</td>
				<td><span class='badge'>$v[totMhs]</span></td>
			";
			
			$TotP=0;
			$TotM=0;
			$Tot=0;
			for($i=1;$i<=14;$i++){
				#   0        1       2     3        4      5      6   7     8
				#DsMasuk|DsKeluar|Menit|DsGetFid|DsStat|TotMhs|Total|Tsesi|tpr
				$d=explode('|',$v[$i]);
				#if($i==1){echo"<td>$d[3]</td>";}
				
				if($d[3]>0){
					if($TotP < $v['jdwl_sesi']){
						$TotP++;
						$TotM+=$d[5];
						$Tot+=$d[3];
					}
				}
				echo'<td><span class="badge" style="background:'.($d[3]==1?'green;':'red;').'">'.($d[3]==1?$d[4]:0)."</span></td>";
			}
			echo "
				<td>$v[jdwl_sesi]</td>
				<td>$TotP</td>
				<td>".( round((($TotP>$v['jdwl_sesi']?$v['jdwl_sesi']:$TotP)/$v['jdwl_sesi'])*100))."%</td>
				<td>".round($TotM/$Tot*100)."%</td>";
				$TP+=( round((($TotP>$v['jdwl_sesi']?$v['jdwl_sesi']:$TotP)/$v['jdwl_sesi'])*100));
				$TM+=round($TotM/$Tot*100);
			echo"</tr>";
		}
		echo'
		<tr><th colspan="19"><span style="float:right"> Total %</span></th><th>'.round($TP/$n).'</th><th>'.round($TM/$n).'</th></tr>
		</table></div></div>';
	}
	?>
	</div>
</div>
