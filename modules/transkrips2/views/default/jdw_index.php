<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Funct;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */
//echo str_repeat('0',3);
//print_r(\app\modules\transkrip\controllers\ModController::Akses(64));
$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;

$mod=app\models\Jurusan::find()->where("jr_jenjang='s2'")->orderBy(['jr_jenjang'=>SORT_DESC])->all();
$Var=ArrayHelper::map($mod,'jr_id',function($model,$defaultValue){return $model->jr_jenjang." ".@$model->jr_nama;});

$VarKur=ArrayHelper::map(
	app\models\Kurikulum::find()
	->where("kr_kode in(select distinct kr_kode from tbl_kalender where jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2'))")
	->orderBy(['substring(kr_kode,2,4)'=>SORT_DESC,'substring(kr_kode,1,1)'=>SORT_DESC,])
	->all(),'kr_kode',function($model,$defaultValue){return $model->kr_kode." : ".@$model->kr_nama;}		
);		



?>
<div class="jadwal-index">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>	
<div class="angge-search">

    <?php $form = ActiveForm::begin([
        'action' => ['jdw'],
        'method' => 'get',
    ]); ?>
<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<div class="panel-body">
    <?= 
		$form->field($searchModel, 'kr_kode')->widget(Select2::classname(), [
			'data' =>$VarKur,
			'language' => 'en',
			'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
    <?= 
		$form->field($searchModel, 'jr_id')->widget(Select2::classname(), [
			'data' =>$Var,
			'language' => 'en',
			'options' => ['placeholder' => 'Jurusan'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
	 
    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['akademik/jdw'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
<br /><br />

    <?php 
	if(isset($_GET['JadwalSearch']['kr_kode']) && !empty($_GET['JadwalSearch']['kr_kode'])){
	
	//Pjax::begin(); 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'exportConfig'=>[
			'pdf'=>[
				'filename' => 'Jadwak',
				'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
				'iconOptions' => ['class' => 'text-danger'],
				'config' => [
					'options' => [
						'title' => $title,
					],
				]
			]
		]
		,
        'toolbar'=> [
            ['content'=>false
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['akademik/jdw-create'],['class'=>'btn btn-success']).''.
                //Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
			'{export}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return $model->jr_jenjang." ".$model->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
				'pageSummary'=>'Total',
			],			
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
				'attribute'=>'mtk_nama',
				'value'=>function($model){return Html::decode($model->mtk_nama);}
				
			],
			[
				'attribute'=>'ds_nm',
				'filter'=>true,
				
			],			
			[
				'attribute'=>'jumabs',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i>',
				'width'=>'5%',
				'value'=>function($model){
					return Html::a($model->jum,
					Yii::$app->urlManager->createUrl(
						['akademik/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
					);
				},
			],
			[
				'header'=>'<center>Nilai</center>',
				'mergeHeader'=>true,
				'format'=>'raw',
				'value'=>function($model){
					return "<center>".(\app\models\Funct::StatNilDos($model->jdwl_id)>0 ? 
					'<i class="glyphicon glyphicon-ok"></i></i>':
					'<i class="glyphicon glyphicon-remove"></i>')."</center>";
				},
				
			],
			[
				'header'=>'<center>Vakasi</center>',
				'mergeHeader'=>true,
				'format'=>'raw',
				'value'=>function($model){
					$v="<center>";
					if($model->Lock >0){
						if( 
							array_key_exists('1',Funct::StatLock($model->Lock)) ||
							array_key_exists('10',Funct::StatLock($model->Lock)) ||
							array_key_exists('100',Funct::StatLock($model->Lock)) ||
							array_key_exists('1000',Funct::StatLock($model->Lock)) ||
							array_key_exists('10000',Funct::StatLock($model->Lock)) 
						  ){
							$v.="I";
						}
						if(array_key_exists('100000',Funct::StatLock($model->Lock))){
							$v.=", II";
						}
					}else{$v.="0";}
					$v.="</center>";
					
					return $v;
				},
				
			],
			[
				'header'=>'<center>Transkrip</center>',
				'mergeHeader'=>true,
				'format'=>'raw',
				'value'=>function($model){
					return "<center>".( $model->Lock>=64 ? 
					Html::a('<i class="glyphicon glyphicon-ok"></i>',['nilai/dok-nil','id'=>$model->jdwl_id]
						,['title'=>'Transfer Transkrip','target'=>'_blank']
					):
					(\app\models\Funct::StatNilDos($model->jdwl_id)>0?
					Html::a('<i class="glyphicon glyphicon-remove"></i>',['nilai/dok-nil','id'=>$model->jdwl_id]
						,['title'=>'Transfer Transkrip','target'=>'_blank']
					):'<i class="glyphicon glyphicon-remove"></i>'
					
					)
					)."</center>";
				},
				
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); //Pjax::end(); 
	}
	?>

</div>
