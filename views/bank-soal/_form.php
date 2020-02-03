<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


/**
 * @var yii\web\View $this
 * @var app\models\BankSoal $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bank-soal-form">

    <?php $form = ActiveForm::begin([
			'type'=>ActiveForm::TYPE_HORIZONTAL,
			'options' => ['enctype'=>'multipart/form-data'],
		]); 
		echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'mtk_kode'=>[
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => 
						ArrayHelper::map(app\models\Matkul::find()->all(),'mtk_kode',
							function($model,$defaultValue){
								return $model->mtk_kode." : ".$model->mtk_nama;
							}		
						),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],
			],
            'file'=>[
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\FileInput',
				'widgetOption'=>[
					'accept' => '*',
					'multiple' =>false,
					'pluginOptions'=>[
						'uploadUrl'=>"app/modules/file/",
						//'allowedFileExtensions'=>["jpg", "png", "gif"],
					]
				]
			],
            'jenis'=>[
					'type'=>Form::INPUT_RADIO_LIST, 
                    'items'=>[true=>'Essay', false=>'PG'], 
                    'options'=>['inline'=>true]				
			],
            'jml_soal'=>[
				'type'=> Form::INPUT_TEXT,
			],
        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
