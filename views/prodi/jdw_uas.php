<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal UAS '.(isset($_SESSION['Ckln'])? app\models\Funct::AKADEMIK()[$_SESSION['Ckln']]:'');
$this->params['breadcrumbs'][] = $this->title;
//echo date('Y-M-d H:i:s',strtotime('2015-02-26 00:00:00.000'));
?>

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,]); ?>
    <div class="jadwal-form">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
	<?= 
 	Form::widget([
    'formName'=>'kalender',
 
    'attributes'=>[
        'tahun'=>[
            'Label'=>'Tahun',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::AKADEMIK(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Tahun Akademik ',
                    'multiple' =>false,							
                ],
            ],
        ],
		        
        'actions'=>[
            'type'=>Form::INPUT_RAW, 
            'value'=>'<div style="text-align: right; margin-top: 20px">' . 
                Html::button('<i class="glyphicon glyphicon-search"></i> Cari', ['type'=>'submit', 'class'=>'btn btn-primary','value'=>'cari']) . 
                '</div>'
        ],				
    ]
 ])
 ?>
<?php ActiveForm::end(); ?>
</div><br /><br />


<div class="jadwal-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['akademik/report-jadwal-uas'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'bn.kln.jr.jr_nama',
				'value'=>function($model){
					return $model->bn->kln->jr->jr_jenjang." ".$model->bn->kln->jr->jr_nama;
				}
				
			],			
			[
				'attribute'=>'bn.kln.pr.pr_nama',
				
			],			
			[
				'label'=>'Jadwal',
				'contentOptions'=>['class'=>'col-xs-1',],
				
				'value'=>function($model){
					return $model->jdwl_masuk." - ".$model->jdwl_keluar;
				},
				'filter'=>@app\models\Funct::HARI(),
			],
			[
				'attribute'=>'jdwl_kls',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
			[
				'attribute'=>'bn.mtk.mtk_nama',
				'filter'=>true,
				
			],
			[
				'attribute'=>'jdwl_uas',
				'format'=>'raw',
				'value'=>function($model){
					
					return $model->jdwl_uas."<br />".$model->jdwl_uas_out;	
				}
			],
			[
				'attribute'=>'rg_uas',
				'width'=>'10%',
				'value'		=> function($model){
					return app\models\Funct::RUANG()[$model->rg_uas];
					//return $model->rg->rg_nama;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::RUANG(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
				
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,

        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal UAS',
    	]
    ]); Pjax::end(); ?>

</div>
