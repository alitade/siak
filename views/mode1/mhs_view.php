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
?>
<div class="mahasiswa-view">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <p>
        <?= ""//Html::a('<i class="glyphicon glyphicon-list-alt"></i> KHS ( Kartu Hasil Study )', ['akademik/mhs-khs', 'id' => $model->mhs_nim],['class'=>'btn btn-warning']) ?>
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
			'status',
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
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
						['/mode1/mhs-krs','id'=>$_GET['id'],'kode'=>$key],['title' =>'Detail']
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
