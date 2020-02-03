<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DepDrop;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="matkul-form">
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Matakuliah</div>
    <div class="panel-body">
    <?php 
	//print_r($model->getErrors());
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'mtk_kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\models\Funct::MTK(2),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
            ],
		], 
		'mtk_nama'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\models\Funct::MTK(3),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
            ],
		], 
		'jr_id'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::JURUSAN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'penanggungjawab'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::DSN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '...',
					
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		//'mtk_sesi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Sesi...']], 
		'mtk_sub'=>[
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
						'depends'		=>	['matkul-jr_id'],
						'url' 			=> 	Url::to(['/akademik/klass']),
						'loadingText' 	=> 	'Loading...',
				],
            ],
		], 
		'mtk_semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'...']], 
		'mtk_kat'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['0'=>'Teori','1'=>'Praktek','2'=>'Teori + Praktek','3'=>'Tugas Besar','4'=>'Teori + Praktek + Tugas Besar','5'=>'Teori + Tugas Besar'],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'mtk_sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'...']], 
    ]


    ]);
    ?>
	
    <div class="panel-body">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
    </div>
    
</div>
