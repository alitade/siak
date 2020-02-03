<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\transkrip\models\NilaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nilai Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-primary">
	<!-- div class="panel-heading"></div -->
    <div class="panel-heading"></div>

    <div class="panel-body">
    <?php $form = ActiveForm::begin(['method' => 'get',]); ?>
    <?= 
		$form->field($searchModel, 'jr_id')->widget(Select2::classname(), [
			'data' =>app\models\Funct::JURUSAN(),
			'language' => 'en',
			'options' => ['placeholder' => 'Jurusan'],
			'pluginOptions' => [
				'allowClear' => true
			],
		])->label('Jurusan');
	 ?>
	 
    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['esbed/jdw'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
    
	<div class="panel-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'ANGKATAN',
            'NAMA',
            'npm',
            'MATKUL',
            'sks',
            'semester',
			'huruf',
            //['class' => 'kartik\grid\ActionColumn'],
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
    ]); ?>

    </div>
</div>

