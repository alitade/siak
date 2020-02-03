<?php

use yii\helpers\Html;
# form widget
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

use kartik\datecontrol\DateControl;

#Chart widget
use kongoon\orgchart\OrgChart;


use yii\helpers\Url;
use app\models\Funct;
use kartik\select2\Select2;
use app\models\Jurusan;
use app\models\Mahasiswa;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

// new
use fedemotta\datatables\DataTables;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Kehadiran Perkuliahn';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading"><h4><i class="fa fa-building"></i> Kehadiran Perkuliahan</h4></div>
    <div class="panel-body">
		<?php 
        $form = ActiveForm::begin(); 
        echo 
        Form::widget([
            'form' => $form,
            'formName' =>'chart',
            'columns' => 4,
            'attributes' => [
                'kr'=>[
					'label'=>'Tahun Akademik',
					'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
					'options'=>[
						'data' => 
							ArrayHelper::map(app\models\Kalender::find()->all(),'kr_kode',
								function($model,$defaultValue){
									return $model->kr->kr_kode." : ".$model->kr->kr_nama;
								}		
							),
						'options' => [
							'fullSpan'=>6,
							'placeholder' => 'Tahun Akademik',
						],
						'pluginOptions' => [
							'allowClear' => true
						],
					],
                ], 
				/*
                'jr'=>[
					'label'=>'Jurusan',
					'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
					'options'=>[
						'type'=>2,
						'options' => [
							'fullSpan'=>6,
							'placeholder' => '... ',
						],
						'select2Options'=>	[
							'pluginOptions'=>['allowClear'=>true]
						],
						'pluginOptions' => [
								'depends'		=>	['chart-kr'],
								'url' 			=> 	Url::to(['/rektor/klnjur']),
								'loadingText' 	=> 	'Loading...',
						],
					],
                ], 
                'pr'=>[
					'label'=>'Program',
					'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
					'options'=>[
						'type'=>2,
						'options' => [
							'fullSpan'=>6,
							'placeholder' => '... ',
						],
						'select2Options'=>	[
							'pluginOptions'=>['allowClear'=>true]
						],
						'pluginOptions' => [
								'depends'		=>	['chart-jr'],
								'url' 			=> 	Url::to(['/rektor/klnpro']),
								'loadingText' 	=> 	'Loading...',
						],
					],
                ],*/		 
                's'=>[
					'label'=>'Sesi',
					'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
					'options'=>[
						'data' =>[
							1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16
						],
						'options' => [
							'fullSpan'=>6,
							'placeholder' => 'Sesi Pertemuan',
						],
						'pluginOptions' => [
							'allowClear' => true
						],
					],
                ], 

				/*
                'parent'=>[
                    'type'=>Form::INPUT_WIDGET,
                    'label'=>'Induk Departemen',
                    'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'pluginOptions' => ['allowClear' => true],
                        'data' => app\models\Dept::Dept(),
                        'options' => [
                            'fullSpan'=>6,
                            'multiple' =>false,
                            'placeholder'=>'Induk',
                            

                        ]
                    ],
                ], 
				*/
            ]
        ]);
        ?>
        <?= Html::submitButton('<i class="fa fa-save"></i> Cari Data', ['class' => 'btn btn-shadow btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="panel-body">
	<div class="col-md-12">
    <?=($Head!=""?"<center><h4>".$Head."</h4></center>":"")
	?>
<?= DataTables::widget([
    'dataProvider' => $dataProvider,
    'columns' =>$columns,
	'clientOptions' => [
    	"lengthMenu"=> [[20,-1], [20,Yii::t('app',"All")]],
    	"info"=>true,
    	"responsive"=>true, 
		//"scrollY"=>600,
		"scrollX"=>true,
		"dom"=> 'lfTrtip',
		"tableTools"=>[
        "aButtons"=> [  
			["sExtends"=> "xls",
            "oSelectorOpts"=> ["page"=> 'current']
            ],[
            "sExtends"=> "pdf",
            "sButtonText"=> Yii::t('app',"Save to PDF")
            ],[
            "sExtends"=> "print",
            "sButtonText"=> Yii::t('app',"Print")
            ],
        ]
    ]		
		
	]
]);?>
</div>
<div>