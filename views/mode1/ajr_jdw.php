<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="page-header">
        <h3><?php 
			echo $ModBn->kln->kr_kode." ( ".$ModBn->ds->ds_nm." : ".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama." ) ";
		?></h3>
    </div>

    <?php 	
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'toolbar'=> [
            '{toggleData}',
			'{export}'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'=>'jdwl_hari',
				'value'=>function($model){
					return @app\models\Funct::HARI()[@$model->jdwl_hari];
				},
				'filter'=>@app\models\Funct::HARI(),
			],
			[
				'attribute'=>'jdwl_masuk',
				'value'=>function($model){return $model->jadwal;},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'jdwl_kls',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
			
			[
				'attribute'=>'rg_kode',
				'width'=>'10%',
				'value'		=> function($model){return $model->rg->rg_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::RUANG(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
				
			],
	
			[
				'attribute'=>'jum',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i> Total',
				'width'=>'5%',
				'value'=>function($model){
					return $model->jum;
				},
				
				
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i>'.$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama." : ".$ModBn->kln->pr->pr_nama,
			'after'=>false,
    	]
    ]); Pjax::end(); 
	?>
</div>
