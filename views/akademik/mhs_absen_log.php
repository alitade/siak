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
        <?= "";//Html::a('<i class="glyphicon glyphicon-edit"></i>', ['akademik/mhs-update', 'id' => $model->mhs_nim], ['class' => 'btn btn-primary']) ?>
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
			//'krs_id',
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
        'enableEditMode'=>false,
    ]) ?>
	<br /><br />
    <?php 
	echo 
	GridView::widget([
		'dataProvider'=>$ThnAkdm,
		'columns' => [
			['class'=>'kartik\grid\SerialColumn'],
			//['attribute'=>'Kelas',],
			//['attribute'=>'Kode','pageSummary'=>'Total',],
			[
				'attribute'	=> 'Matakuliah',
				'format'=>'raw',
				'value'		=> function($model){
					$ket='';
					$ket.= $model["Kode"].": ";
					$ket.=$model["Matakuliah"];
					$ket.=" (".$model["Kelas"].")";
					return $ket;
				}
			],
			[
				'attribute'	=> 'Total','label'=>'T.Sesi',				
				'value'		=> function($model){return $model["Total"];}
			],
			[
				'attribute'	=> 'Pertemuan','label'=>'T.Hadir',				
				'value'		=> function($model){return $model["Pertemuan"];}
			],
			[
				'attribute'	=> 'Persen','label'=>'(%)',				
				'value'		=> function($model){return number_format($model["Persen"],2);}
			],
			
			/////// sesi
			[
				'attribute'	=> '1','format'	=>'raw','value'=> function($model){
					if($model['1']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '2','format'	=>'raw','value'=> function($model){
					if($model['2']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '3','format'	=>'raw','value'=> function($model){
					if($model['3']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '4','format'	=>'raw','value'=> function($model){
					if($model['4']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '5','format'	=>'raw','value'=> function($model){
					if($model['5']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '6','format'	=>'raw','value'=> function($model){
					if($model['6']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '7','format'	=>'raw','value'=> function($model){
					if($model['7']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '8','format'	=>'raw','value'=> function($model){
					if($model['8']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '9','format'	=>'raw','value'=> function($model){
					if($model['9']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '10','format'	=>'raw','value'=> function($model){
					if($model['10']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '11','format'	=>'raw','value'=> function($model){
					if($model['11']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '12','format'	=>'raw','value'=> function($model){
					if($model['12']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '13','format'	=>'raw','value'=> function($model){
					if($model['13']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			[
				'attribute'	=> '14','format'	=>'raw','value'=> function($model){
					if($model['14']==0){return '<i class="glyphicon glyphicon-remove-circle"  style="color: red;"></i>';}else{return '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';}
				}
			],
			
		],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Kode Kurikulum :'.$KODE,
        ]
		
	]); 
 	?>


</div>
