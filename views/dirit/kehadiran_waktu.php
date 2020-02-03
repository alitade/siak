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

$this->title = 'Kehadiran Perkuliahan Waktu';
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
<br /><br />
<div class="panel-body">
    <?php 

	if($KrKd>0){
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		//print_r($Gthn);
		echo '<div class="col-xl-12" style="overflow:auto;height:500px">
		Ket Kolom Sesi:<br />
			HH:mm-HH:mm (Jam Kehadiran)<br />
			mm\'|mm\' ( Durasi Keterlambatan Dalam Menit | Durasi Perkuliahan Dalam Menit)<br />
			<span class="label" style="background:green;collor:white;border:none;font-size:13px;padding:2px">Hadir</span>
			<span class="label" style="background:red;collor:black;border:none;font-size:13px;padding:2px">Tidak Hadir</span> 
		'.
		
		$data.'
		<div style="width:100%;">'.
		Html::a('Cetak Pdf', ['dirit/cetak-kehadiran-dosen','kr'=>$Gthn['thn'],'t'=>'1','jr'=>$Gthn['jr']],['target'=>'blank_','class' => 'btn btn-primary']).'
		<table class="table table-bordered table-striped"  style="font-size:10pt;font-weight:bold;width:auto">
		<thead>
			<tr>
				<th rowspan="2" width="1%">#</th>
				<th rowspan="2">Matakuliah</th>
				<th colspan="14">SESI</th>
			<tr>
				';
		for($i=1;$i<=14;$i++){echo'<th  style="width:30pt">'.$i.'</th>';}
		echo'</tr></thead>';
		$TP=0;
		$TM=0;
        $HeadDs="";
        $HeadTm="";
		$time="";
		foreach($QueKuliah as $k=>$v){

			if($HeadDs!=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)" ){
				$HeadDs=$v['ds_nm']." : ".Funct::HARI()[$v['jdwl_hari']]." $v[jdwl_masuk]-$v[jdwl_keluar] ($v[Tm] Mnt.)";
				$time="";
				echo"<tr><td colspan='15'>$HeadDs</td></tr>";
				$rowspan=0;
			}

            $rowspan++;
			$n++;
			$fid=$v['fid'];
			
			echo"<tr>
				<td>$n</td>
				<td>$v[mtk_kode]: $v[mtk_nama] ($v[jdwl_kls])</td>
			";
			
			$TotP=0;
			$TotM=0;
			$Tot=0;

            if($HeadTm!=$HeadDs){
                $HeadTm=$HeadDs;
                //echo"<tr><td colspan='15'>$HeadDs</td></tr>";

            }
			for($i=1;$i<=14;$i++){


				#   0        1       2     3        4      5      6   7     8
				#DsMasuk|DsKeluar|Menit|DsGetFid|DsStat|TotMhs|Total|Tsesi|tpr |
				$d=explode('|',$v[$i]);
				$d[0]=substr($d[0],0,5);
				$d[1]=substr($d[1],0,5);
				if($i==1){echo"<!-- td>$d[5]</td -->";}
				if($d[4]>0){
					if($TotP < $d[7]){
						$TotP++;$TotM+=$d[6];
						$Tot+=$d[5];
					}
				}
				echo'<td style="color:'.($d[4]==='0' || !$d[4]?'red;':($d[4]==='1'?'green;':'red;')).'">'
				#." $rowspan "
                .($d[4]==1?
				#'<span class="label" style="color:inherit;font-size:13px">'.$d[6]."</span><br />"
				#.'<span class="label" style="color:inherit;font-size:13px">'.($d[10]?$d[10]:"-")."-".($d[11]?$d[11]:"-")."</span><br />"
				'<span class="label" style="color:inherit;font-size:13px">'.($d[0]?$d[0]:"-")."-".($d[1]?$d[1]:"-")."</span><br />"
				.'<span class="label" style="color:inherit;font-size:13px">'.( $d[9] ? '<span style="color:'.($d[9]>0?'red;':'green;').'">'.$d[9]."</span>" :"-")." | ".($d[2]?$d[2]:"-")."</span>":'X'
				)."
				</td>";
			}
				$TP+=( round((($TotP>$d[7]?$d[7]:$TotP)/$d[7])*100));
				$TM+=round($TotM/$Tot*100);
			echo"</tr>";
		}
		echo'
		</table></div></div>';
	}
	?>
	</div>
</div>
