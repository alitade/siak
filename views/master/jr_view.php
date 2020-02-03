<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 */

$this->title = $model->jr_jenjang." ".$model->jr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Jurusan', 'url' => ['jr']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="jurusan-view">
	<?php if($model){?>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
        'panel'=>[
	        'heading'=>'Jurusan : '.$model->jr_jenjang.' '. $model->jr_nama,
	        'type'=>DetailView::TYPE_PRIMARY,
    	],
        'attributes' => [
            'jr_id',
			[
				'attribute'=>'fk_id',
				'value'=>$model->fk->fk_nama,
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::FK(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
            'jr_kode_nim',
            'jr_nama',
			[
				'attribute'=>'jr_jenjang',
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => ['S2'=>'S2','S1'=>'S1','D3'=>'D3'],
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
			[
				'attribute'=>'jr_stat',
				'value'=>($model->jr_stat==1?'Aktif':'Non Aktif'),
				'type'=>DetailView::INPUT_SWITCH,
				'widgetOptions'=>[
					'pluginOptions' => [
						'onText'=>'Aktif',
						'offText'=>'Non Aktif',
					],
					'value'=>$model->jr_stat
				],
			
			],
        ],
        
        'enableEditMode'=>false,
    ]) ?>
	<?php
	}else{
		echo "data tidak ada";	
	}
	?>
</div>
