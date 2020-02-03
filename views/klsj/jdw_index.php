<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
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
			'data' =>app\models\Funct::AKADEMIK(),
			'language' => 'en',
			'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
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
	//echo Html::a('<i class="glyphicon glyphicon-refresh"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-danger','target'=>'_blank']);
	//Pjax::begin();
	//Menampilkan <b>{begin, number}-{end, number}</b> dari <b>{totalCount, number}</b> {totalCount, plural, one{item} other{item}}
	echo $d= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary'	=> 'Menampilkan {begin, number}-{end, number} dari {totalCount, number}',
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['akademik/jdw-create'],['class'=>'btn btn-success']).''.
				Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-info','target'=>'_blank'])
                //Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
			//'{export}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> function($model){return $model->jr_jenjang." ".$model->jr_nama;},
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
				'value'		=> function($model){return @$model->pr_nama;},
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
				'label'=>'SKS',
				'value'=>function($model){
					return Html::decode($model->bn->mtk->mtk_sks);
				},
				'pageSummary'=>true,
				
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
				'attribute'=>'TotSesi',
				'format'=>'raw',
				'header'=>'&sum;S.',
				'width'=>'1%',
				'value'=>function($model){
					return $model->TotSesi;
				},
			],
        ],
        'responsive'=>false,
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
