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
<?php if($ModBn){?>
    <div class="page-header">
        <h3><?php 
			echo $ModBn->kln->kr_kode." ( ".$ModBn->ds->ds_nm." : ".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama." ) ";
		?></h3>
    </div>
	<?= 
        $this->render('../akademik/schedule__form', [
        'model' => $model2,
        'mtk' => $ModBn->mtk,
    ]) ?>	

    <?php 	
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['akademik/jdw-create', 'id'=>$model->id],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
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
					return Html::a($model->jum,
					Yii::$app->urlManager->createUrl(
						['bisa/ajr-nilai','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Nilai'),]
					);
				},
				
				
			],
			[
				'attribute'=>'jumabs',
				'label'=>'Nilai',
				'format'=>'raw',
				'width'=>'5%',
				'value'=>function($model){
					return Html::a($model->jumabs,
					Yii::$app->urlManager->createUrl(
						['bisa/ajr-nilai','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Nilai'),]
					);
				},
				
				
			],
 			[     
            'width' => '5%',
            'label' => 'Absensi',
            'format' => 'raw',
            'value' => function($model){
                    return Html::a('<i class="glyphicon glyphicon-list"></i>',
                     ['attendance', 'id' => $model->jdwl_id,'matakuliah'=>$model->mtk_kode,'sort'=>'id'],
                     ['data-pjax'=> '0','class' => 'btn btn-info','target'=>'_blank']);
            },

            
       		 ],	
       		 [     
            'width' => '5%',
            'label' => 'Cross Schedule',
            'format' => 'raw',
            'value' => function($model){
            		$krs = Krs::find()->where(["jdwl_id" => $model->jdwl_id])->all();
            		if(count($krs)>1){
	 	                 return Html::a('<i class="glyphicon glyphicon-list"></i>',
  		                 ['bisa/ajr-split', 'id' => $model->jdwl_id],
	                     ['data-pjax'=> '0','class' => 'btn btn-info','target'=>'_blank']);
            			}
            		else{
            			return 'None';
            		}
            		},
            
       		 ],			
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i>'.$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama." : ".$ModBn->kln->pr->pr_nama,
    	]
    ]); Pjax::end(); 
	
	}
	else{
		echo "Data tidak ada";
	}
	?>
    
</div>
