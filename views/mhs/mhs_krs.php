<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = @$model->mhs->people->Nama;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['/mhs/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/mhs/view','id'=>$model->mhs_nim]];
?>
<div class="mahasiswa-view">
    <p> </p>
    <?= $this->render('/mhs/_view', ['model' => $model,]) ?>
    <?php
	echo 
	GridView::widget([
		'dataProvider'=>$ThnAkdm,
		'columns' => [
			['class'=>'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'Matakuliah',
				'label'=>'Matakuliah (Kls)',
				'format'=>'raw',
				'value'		=> function($model){
					$ket= '<span title="'.$model['Id'].'|'.$model['Program'].'">'.$model['Kode'].' | '.$model["Matakuliah"]." ($model[Kelas]) </span>";
					if(is_array(\app\models\Funct::ATT($model['Id']))){
						$ket.=" <div  style='font-size:12px;color:red;font-weight:bold'>
							*Tidak Ada Nilai: ".implode(", ",\app\models\Funct::ATT($model['Id']))."
						</div>";
					}	
					return $ket;
				},
				'pageSummary'=>(\app\models\Funct::acc('/mhs/nilai')?"TOTAL":false),
			],
            [
                'attribute'=>'Dosen',
            ],
            [
                'attribute'=>'Jadwal',
            ],
			[
				'label'=>'Absensi',
				'format'=>'raw',
				'value'		=> function($model){
					$abs = \app\models\Funct::absen3($model['jdwl_id'],$model['Id']);
					#return $model['Id'];
                    $abs =Yii::$app->db->createCommand(" SELECT dbo.persensiMhs($model[Id]) absen ")->queryOne();
                    if($abs){return number_format($abs['absen'])."%";}
                    return "0%";

                    #return "<b>".round($abs['persen'])."% [<sup>$abs[pertemuan]</sup>/<sub>$abs[kehadiran]</sub>]</b>" ;
				}
				//'attribute'=>'Dosen',
				//'pageSummary'=>'Total',
			],
			[
				'attribute'=>'SKS',
				'label'=>'SKS',
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM,
				'footer'=>true,
                'visible'=>(\app\models\Funct::acc('/mhs/nilai')?:false),
			],
            [
                'attribute'=>'Grade',
                'label'=>'Grade',
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM,
                'footer'=>true,
                'visible'=>(\app\models\Funct::acc('/mhs/nilai')?:false),
            ],
			[
				'attribute'=>'Total',
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM,
				'footer'=>true,
                'visible'=>(\app\models\Funct::acc('/mhs/nilai')?:false),
			],
			
		],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
		'showPageSummary' => true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Kode Kurikulum :'.$Tahun,
			'footer'=>false,
            'after'=> ' IP : '.number_format($IP,2),
            'before'=>false,
        ]
		
	]); 
 	?>


</div>
