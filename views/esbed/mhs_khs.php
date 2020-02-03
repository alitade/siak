<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = " Kartu Hasil Studi : ".$model->mhs->people->Nama;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-view">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Mahasiswa : ' . $model->mhs->people->Nama,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
			[
				'attribute'=>'mhs_nim',
				'displayOnly'=>true,
			],	
			[
				'label'=>'Nama',
				'value'=>$model->mhs->people->Nama,
				'displayOnly'=>true,
			],	
            'mhs_angkatan',
			[
				'attribute'=>'jr_id',
				'value'=>app\models\Funct::JURUSAN()[$model->jr_id],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::JURUSAN(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
			[
				'attribute'=>'pr_kode',
				'value'=>$model->pr->pr_nama,
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::Program(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
			[
				'attribute'=>'mhs_stat',
				'value'=>($model->mhs_stat==1?'Aktif':'Non Aktif'),
				'type'=>DetailView::INPUT_SWITCH,
				'widgetOptions'=>[
					'pluginOptions' => [
						'onText'=>'Aktif',
						'offText'=>'Non Aktif',
					],
					'value'=>$model->mhs_stat
				],
			
			],
			[
				'attribute'=>'ds_wali',
				'value'=> app\models\Funct::DSN()[$model->ds_wali],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                //'data' => app\models\Funct::Program(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
			[
				'label'=>' IPK ',
				'value'=>$IP,
			],
        ],
        'enableEditMode'=>false,
    ]) ?>
	<br /><br />
    <?php 
	echo '
	<table class="table table-hover table-bordered table-condensed">
	';
	$kode='';
	foreach($DataSemester as $d){
		//$d=array_merge($d);
		$grade=0;
		if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
			$grade=app\models\Funct::Mutu($d['krs_grade']);
		}

		if($kode!=$d['mtk_kode']){
			$kode=$d['mtk_kode'];	
			$total 				= $d['mtk_sks'] * $grade;
			@$TotKrs[$d['mtk_semester']]		= $TotKrs[$d['mtk_semester']]+$d['mtk_sks'];
			@$TotGrade[$d['mtk_semester']]   	= $TotGrade[$d['mtk_semester']]+$total;
			@$content[$d['mtk_semester']]=@$content[$d['mtk_semester']]."
				<tr>
					<td> $d[mtk_kode] </td>
					<td> $d[mtk_nama] </td>
					<td> $d[mtk_sks] </td>
					<td> $d[ds_nidn] </td>
					<td> $d[krs_grade] </td>
					<td> $total </td>
				</tr>
			";
		}else{
			if($grade!=0){
			$total 				= $d['mtk_sks'] * $grade;
			if(isset($TotKrs[$d['mtk_semester']]))
			unset($TotKrs[$d['mtk_semester']]);

			if(isset($TotGrade[$d['mtk_semester']]))
			unset($TotGrade[$d['mtk_semester']]);

			if(isset($content[$d['mtk_semester']]))
			unset($content[$d['mtk_semester']]);
			
			@$TotKrs[$d['mtk_semester']]		= $TotKrs[$d['mtk_semester']]+$d['mtk_sks'];
			@$TotGrade[$d['mtk_semester']]   	= $TotGrade[$d['mtk_semester']]+$total;
			@$content[$d['mtk_semester']]=@$content[$d['mtk_semester']]."
				<tr>
					<td> $d[mtk_kode] </td>
					<td> $d[mtk_nama] </td>
					<td> $d[mtk_sks] </td>
					<td> $d[ds_nidn] </td>
					<td> $d[krs_grade] </td>
					<td> $total </td>
				</tr>
			";				
			}
		}
	}
	echo "<p><h4>KHS per Semester</h4></p><hr>";
	foreach ($content as $k=>$v){
	echo '
		<strong>Semester '.$k.'</strong>
		<table class="table table-hover table-bordered table-condensed">
			<tr>
				<th> Kode </th>
				<th> Matakuliah </th>
				<th> SKS </th>
				<th> Dosen </th>
				<th> Grade </th>
				<th> NA </th>
			</tr>
		'.$v.'
		<tr>
			<td colspan="6" align="right"> <b>IP ( NA /Total SKS) : '.$TotGrade[$k].' / '.$TotKrs[$k].' = '.@number_format(($TotGrade[$k]/$TotKrs[$k]),2).'</b> </td>
		</tr>
	</table><br />';
	}
	
	
	echo "<p><h4>KHS per Tahun Akademik</h4></p><hr>";	
	$kode='';
	$content=array();
	foreach($DataTahun as $d){
		//$d=array_merge($d);
		$grade=0;
		if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
			$grade=app\models\Funct::Mutu($d['krs_grade']);
		}

		if($kode!=$d['mtk_kode']){
			$kode=$d['mtk_kode'];	
			$total 				= $d['mtk_sks'] * $grade;
			@$TotKrs[$d['kr_kode']]		= $TotKrs[$d['kr_kode']]+$d['mtk_sks'];
			@$TotGrade[$d['kr_kode']]   	= $TotGrade[$d['kr_kode']]+$total;
			@$content[$d['kr_kode']]=@$content[$d['kr_kode']]."
				<tr>
					<td> $d[mtk_kode] </td>
					<td> $d[mtk_nama] </td>
					<td> $d[mtk_sks] </td>
					<td> $d[ds_nidn] </td>
					<td> $d[krs_grade] </td>
					<td> $total </td>
				</tr>
			";
		}else{
			if($grade!=0){
			$total 				= $d['mtk_sks'] * $grade;
			if(isset($TotKrs[$d['kr_kode']]))
			unset($TotKrs[$d['kr_kode']]);

			if(isset($TotGrade[$d['kr_kode']]))
			unset($TotGrade[$d['kr_kode']]);

			if(isset($content[$d['kr_kode']]))
			unset($content[$d['kr_kode']]);
			
			@$TotKrs[$d['kr_kode']]		= $TotKrs[$d['kr_kode']]+$d['mtk_sks'];
			@$TotGrade[$d['kr_kode']]   	= $TotGrade[$d['kr_kode']]+$total;
			@$content[$d['kr_kode']]=@$content[$d['kr_kode']]."
				<tr>
					<td> $d[mtk_kode] </td>
					<td> $d[mtk_nama] </td>
					<td> $d[mtk_sks] </td>
					<td> $d[ds_nidn] </td>
					<td> $d[krs_grade] </td>
					<td> $total </td>
				</tr>
			";				
			}
		}
	}
	
	foreach ($content as $k=>$v){
	echo '
		<strong>Tahun Akademik '.$k.'</strong>
		<table class="table table-hover table-bordered table-condensed">
			<tr>
				<th> Kode </th>
				<th> Matakuliah </th>
				<th> SKS </th>
				<th> Dosen </th>
				<th> Grade </th>
				<th> NA </th>
			</tr>
		'.$v.'
		<tr>
			<td colspan="6" align="right"> <b>IP ( NA /Total SKS) : '.$TotGrade[$k].' / '.$TotKrs[$k].' = '.@number_format(($TotGrade[$k]/$TotKrs[$k]),2).'</b> </td>
		</tr></table><br /><br />';
	}	
	echo'
	
	
	';
/*	
	echo 
	GridView::widget([
		'dataProvider'=>$ThnAkdm,
		'columns' => [
			'Tahun_Akademik',
			'Total_Matakuliah',
			'Total_SKS',
			[
				'label'=>' ',
				'format'=>'raw',
				'value'=>function($data,$key){
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						['/akademik/mhs-krs','id'=>$_GET['id'],'kode'=>$key],['title' =>'Detail']
					);
				}
			]
			
		],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> History',
			
        ]
		
	]); 
	*/
 	?>


</div>
