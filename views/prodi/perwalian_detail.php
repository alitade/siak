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

$this->title = 'Perwalian';
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
    <?php $form = ActiveForm::begin(['action' => ['prw-det'],'method' => 'get',]); ?>
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
			'jdwl.bn.mtk_kode',
			'jdwl.bn.mtk.mtk_nama',
			[	
				'attribute'=>'sks_',
				'label'=>'SKS',
				'width'=>'1%'
			],
			'jdwl.bn.ds.ds_nm',
			
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
