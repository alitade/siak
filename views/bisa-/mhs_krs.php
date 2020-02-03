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
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['mhs/mhs-view','id'=>$model->mhs_nim]];
?>
<div class="mahasiswa-view">
    <p>
        <?= Html::a('<i class="glyphicon glyphicon-edit"></i>', ['akademik/mhs-update', 'id' => $model->mhs_nim], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Mahasiswa : '.@$model->mhs->people->Nama,
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
        'enableEditMode'=>true,
    ]) ?>
	<br /><br />
    <?php 
	echo 
	GridView::widget([
		'dataProvider'=>$ThnAkdm,
		'columns' => [
			['class'=>'kartik\grid\SerialColumn'],
			[
				'attribute'=>'Kode',
				'pageSummary'=>'Total',
			],
			'Matakuliah',
			[
				'attribute'=>'Dosen',
			],
			[
				'attribute'=>'SKS',
				'label'=>'SKS',
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM,
				'footer'=>true	
			],
			'Grade',
			[
				'attribute'=>'Total',
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM,
				'footer'=>true	
			],
			
		],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
		'showPageSummary' => true,
		
		
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Kode Kurikulum :'.$Tahun,
			'footer'=> ' IP : '.number_format($IP,2)
        ]
		
	]); 
 	?>


</div>
