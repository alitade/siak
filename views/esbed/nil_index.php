<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\KrsSearch $searchModel
 */

$this->title = 'Nilai Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="krs-index">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

<div class="angge-search">
<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<div class="panel-body">
    <?php $form = ActiveForm::begin([
        'action' => ['nil'],
        'method' => 'get',
    ]); ?>
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
    <?= 
		$form->field($searchModel, 'jr_id')->widget(Select2::classname(), [
			'data' =>app\models\Funct::JURUSAN(),
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
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['esbed/jdw'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>



    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[	
				'attribute'=>'jr_id',
				'label'=>'Jurusan',
				'value'=>function($model){
					return $model->Jurusan;
				},
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
				'attribute'=>'pr_kode',
				'label'=>'Program',
				'value'=>function($model){
					return $model->Program;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
			],
			[	
				'attribute'=>'mhs_nim',
				'label'=>'NPM',
			],
            'Mahasiswa',
			[	
				'attribute'=>'Matakuliah',
			],
			[	
				'attribute'=>'sks_',
				'width'=>'10px',
			],
			[	
				'attribute'=>'krs_grade',
				'label'=>'Nilai',
				'width'=>'10px',
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
		'toolbar'=>[
 			'{toggleData}',
			'{export}',
 		],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
		'export'=>[
			'excel',
//			'exportContainer'=>['excel'],
			'exportConfig'=>[
				'Excel2007'=>['filename' =>'Nilai'],
				'PDF'=>false
			]
		]
    ]); Pjax::end(); ?>

</div>
