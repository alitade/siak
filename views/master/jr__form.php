<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jurusan-form">

<div class="panel panel-primary">
    <div class="panel-heading"><?= $title ?></div>
        <div class="panel-body">
            <div class="col-lg-8">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'fk_id'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::FK(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Fakultas',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'jr_id'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\models\Funct::Jurusan(2),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Kode Jurusan',
                ],
            ],
		], 
		'jr_nama'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\models\Funct::Jurusan(3),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Nama Jurusan',
                ],
            ],
		], 
		'jr_jenjang'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['S2'=>'S2','S1'=>'S1','D3'=>'D3'],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Jenjang',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		//'jr_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jr Stat...']], 
		//'jr_kode_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kode Nim Jurusan...']], 
		'jr_head'=>[
			'label'=>'Kajur.',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\models\Funct::DSN(3),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Kajur.',
                ],
            ],
		], 
		[
			'type'=>Form::INPUT_RAW,
			'value'=>Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
		]
		
    ]


    ]);
    
    ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
