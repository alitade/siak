<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\web\JsExpression;
use \app\models\Mahasiswa;
use \app\modules\transkrip\models\Wisuda
/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Wisuda $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="wisuda-form">

    <?php 
	$data = Wisuda::find()->distinct(true)->select(['kode'])->all();
	
	$var=[];
	foreach($data as $d){$var[]=strtoupper($d['kode']);}

	$url = \yii\helpers\Url::to(['nilai/data']);
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
	 echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'npm'=>[
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
				'options' => [
					'placeholder' => 'cari NPM ...'
				],
				'pluginOptions' => [
					'allowClear' => true,
					'minimumInputLength' => 4,
					'ajax' => [
						'url' => $url,
						'dataType' => 'json',
						'data' => new JsExpression('function(params) { return {q:params.term}; }'),
						'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
					],
				],

            ],
		], 
		
		'skripsi_indo'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Skripsi Indo...','rows'=> 6]], 
		'skripsi_end'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Skripsi End...','rows'=> 6]], 
		'ds_id_'=>[
			'label'=>'Pembimbing',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => \app\models\Funct::DSN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '...',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 

		'kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>$var,
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
            ],
		], 
		'predikat'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => [
					'Dengan Pujian'=>'Dengan Pujian',
					'Sangat Memuaskan'=>'Sangat Memuaskan',
					'Memuaskan'=>'Memuaskan',
					'Cumloude / Dengan Pujian'=>'Cumloude / Dengan Pujian',
					' '=>' ',
				],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Predikat',
                ],
				'pluginOptions' => ['allowClear' => true],
            ],
		], 
		'nilai'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E',],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Nilai',
                ],
				'pluginOptions' => ['allowClear' => true],
            ],
		], 

		'pejabat1'=>[
			'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pejabat1...']
		], 
		'pejabat2'=>[
			'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pejabat2...']
		], 

		'tgl_lulus'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
