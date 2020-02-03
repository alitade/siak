<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 * @var yii\widgets\ActiveForm $form
 */




?>

<div class="dosen-form">
<div class="panel panel-primary">
    <div class="panel-heading">Update Data</div>
    	<div class="panel-body">
            <div class="col-lg-10">
    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'ds_nidn'=>[
			'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
			'options'=>[
				'dataset' => [['local'=>Funct::DSN(2),'limit'=>10,]],
				'options' => ['fullSpan'=>6,'placeholder'=>'Dosen NIDN'],
			],
		], 
		'ds_nm'=>[
            'label'=>"*) Nama",
			'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
			'options'=>[
				'dataset' => [[
					'local'=>Funct::DSN(3),
					'limit'=>10,
				]],
				'options' => [
					'fullSpan'=>6,
					'placeholder'=>'Nama Lengkap'
				],
			],
		],
		'id_tipe'=>[
            'label'=>"*) Tipe",
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => Funct::TDS(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Tipe Dosen',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],

        ],
		'ds_user'=>[
            'label'=>"*) Username",
			'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
			'options'=>[
				'dataset' => [[
					'local'=>app\models\Funct::DSN(2,'ds_user'),
					'limit'=>10,
				]],
				'options' => [
					'fullSpan'=>6,
					'placeholder'=>'Username'
				],
			],
		], 
		'ds_email'=>[
            'label'=>"*) Email",
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
			'options'=>[
				'dataset' => [[
					'local'=>app\models\Funct::DSN(2,'ds_email'),
					'limit'=>10,
				]],
				'options' => [
					'fullSpan'=>6,
					'placeholder'=>'Email'
				],
			],
		],
        [
            'label'=>false,
            'type'=>Form::INPUT_RAW,
            'value'=>'<div class="bg-info"> <b><i>*) Harus Diisi</i></b></div>'
        ],

    ]


    ]); 
?>
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>

<?php
    ActiveForm::end(); ?>
    		</div>
    	</div>
    </div>
</div>
