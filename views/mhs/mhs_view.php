<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use  app\models\Funct;

$this->title = @$model->mhs->people->Nama;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = $this->title;
$agama=[1=>'Islam','Protestan','Katolik','Hindu','Budha'];

?>

<div class="mahasiswa-view">
    <?= $this->render('/mhs/_view', ['model' => $model,'dataMatkul'=>$dataMatkul,'TRANSKRIP'=>$TRANSKRIP]) ?>
	<br />
    <?php 
	echo 
	GridView::widget([
		'dataProvider'=>$ModKe,
		'columns' => [
			'tahun',
			[
				'attribute'=>'status',
				'value'=>function($model){
					if( $model->sisa <= 0 || $model->status=='Lunas'){
						return "Lunas";
					}
					return "Belum Lunas";
					
				}
			],
		],
		'export'=>false,
		'toolbar'=>false,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,

        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> History Pembayaran',
            'footer'=>false,
        ]
		
	]); 
 	?>
	<br />
    <?php 
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
					return
                        (!Funct::acc('/mhs/krs')?"":Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['/mhs/krs','id'=>$_GET['id'],'kode'=>$key],['title' =>'Detail']))." ".
                        (!Funct::acc('/mhs/absen')?"":Html::a('<span class="glyphicon glyphicon-calendar"></span>',['/mhs/absen','id'=>$_GET['id'],'kode'=>$data['Tahun_Akademik']],['title' =>'Absensi']))." ".
                        (!Funct::acc('/mhs/absen-log')?"":Html::a('<span class="glyphicon glyphicon-time"></span>',['/mhs/absen-log','id'=>$_GET['id'],'kode'=>$data['Tahun_Akademik']],['title' =>'Log Absensi']));
				}
			]			
		],
		'export'=>false,
		'toolbar'=>false,
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> History KRS',
            'footer'=>false,
        ]
		
	]); 
 	?>


</div>
