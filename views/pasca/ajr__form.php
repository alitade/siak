<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\BobotNilai $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bobot-nilai-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kalender'=>[
			'label'=>'Tahun Akademik',
			'options'=>['placeholder'=>'...'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => 
					ArrayHelper::map(app\models\Kalender::find()->all(),'kr_kode',
						function($model,$defaultValue){
							return $model->kr->kr_kode." : ".$model->kr->kr_nama;
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

		'kln_id'=>[
			'label'=>'Program',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
            'options'=>[
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'select2Options'=>	[
					'pluginOptions'=>['allowClear'=>true]
				],
				'pluginOptions' => [
						'depends'		=>	['bobotnilai-kalender'],
						'url' 			=> 	Url::to(['/pasca/klnpro']),
						'loadingText' 	=> 	'Loading...',
				],
            ],
		], 
		'mtk_kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::MTK(1,['jr_id'=>$J]),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 

		'ds_nidn'=>[
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::DSN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
