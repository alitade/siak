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
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = $this->title;
$agama=[1=>'Islam','Protestan','Katolik','Hindu','Budha'];	

?>
<div class="mahasiswa-view">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <p>
        <?= Html::a('<i class="glyphicon glyphicon-list-alt"></i> KHS ( Kartu Hasil Study )', ['akademik/mhs-khs', 'id' => $model->mhs_nim],['class'=>'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Mahasiswa : ' . @$model->mhs->people->Nama." ( ".@$model->mhs->status_mhs." ) ",
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
			[
				// 1
				'columns'=>[
					[
						'label'=>'Nama (NPM)',
						'value'=>@$model->mhs->people->Nama.' ('.$model->mhs_nim.')',
						'displayOnly'=>true,
						'valueColOptions'=>['style'=>'width:30%']
					],
					[
						'label'=>'Asal Sekolah ( Tahun Lulus)',
						'value'=>@$model->mhs->people->asal_sekolah.' ('.@app\models\Funct::TANGGAL(@$model->mhs->people->tahun_lulus).')',
						'displayOnly'=>true,
					],
				],
			],
			[	
				// 2
				'columns'=>[
					[
						'label'		=>'Jurusan (Program)',
						'attribute'	=>'mhs_nim',
						'value'		=>app\models\Funct::JURUSAN()[$model->jr_id].' ('.$model->pr->pr_nama.')',
						'displayOnly'=>true,
						'valueColOptions'=>['style'=>'width:30%']
					],	
					[
						'label'=>'No. KTP',
						'value'=>@$model->mhs->people->no_ktp,
						'displayOnly'=>true,
					],
				],
			],
			[	// 3
				'columns'=>[
					[
						'attribute'=>'mhs_angkatan',
						'value'=>$model->mhs_angkatan." / ".$model->mhs->kurikulum,
						'valueColOptions'=>['style'=>'width:30%']
					],
					[
						'label'=>'Tempat / Tgl. Lahir',
						'value'=>@$model->mhs->people->tempat_lahir.' / '.@app\models\Funct::TANGGAL(@$model->mhs->people->tanggal_lahir),
						'displayOnly'=>true,
					],
				],
			],
			[	// 4
				'columns'=>[
					[
						'label'=>'No. Tlp.',
						'value'=>@$model->mhs->people->no_telepon,
						'displayOnly'=>true,
						'valueColOptions'=>['style'=>'width:30%'],
					],
					[
						'label'=>'Agama',
						'value'=>(isset($agama[@$model->mhs->people->agama])? $agama[@$model->mhs->people->agama]:@$model->mhs->people->agama),
						'displayOnly'=>true,
					],
				],
			],
			[	// 5
				'columns'=>[
					[
						'label'=>'Dosen Wali',
						'attribute'=>'jr_id',
						'value'=>@app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali],
						'valueColOptions'=>['style'=>'width:30%'],
					],
					[
						'label'=>'Alamat Tinggal',
						'value'=>
							@$model->mhs->people->alamat.', '
							.@$model->mhs->people->kota.', '
							.@$model->mhs->people->propinsi.', '
							.@$model->mhs->people->kode_pos.' '
							,
						'displayOnly'=>true,
					],
				],
			],
			[	// 6
				'columns'=>[
					[
						'label'=>false,
						'value'=>false,//$model->pr->pr_nama,
						'valueColOptions'=>['style'=>'width:30%']
					
					],
					[
						'label'=>'Nama Ibu Kandung',
						'value'=>@$model->mhs->people->ibu_kandung,
						'displayOnly'=>true,
					],
				],
			],

/*
			[
				'attribute'=>'mhs_nim',
				'displayOnly'=>true,
			],	
			[
				'label'=>'Nama',
				'value'=>@$model->mhs->people->Nama,
				'displayOnly'=>true,
			],	
            [
				'attribute'=>'mhs_angkatan',
				'value'=>$model->mhs_angkatan." / ".$model->mhs->kurikulum,
			],
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
				'value'=> @app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali],
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
*/
        ],
        'enableEditMode'=>false,
    ]) ?>


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
					Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						['/akademik/mhs-krs','id'=>$_GET['id'],'kode'=>$key],['title' =>'Detail']
					)." ".
					Html::a('<span class="glyphicon glyphicon-calendar"></span>',
						['/akademik/mhs-absen','id'=>$_GET['id'],'kode'=>$data['Tahun_Akademik']],['title' =>'Absensi']
					)." ".
					Html::a('<span class="glyphicon glyphicon-time"></span>',
						['/akademik/mhs-absen-log','id'=>$_GET['id'],'kode'=>$data['Tahun_Akademik']],['title' =>'Log Absensi']
					);
				}
			]			
		],
		'export'=>false,
		'toolbar'=>false,
        'responsive'=>true,
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
