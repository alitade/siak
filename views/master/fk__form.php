<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Fakultas $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="fakultas-form">
<div class="panel panel-primary">
    <div class="panel-heading"><?= $title ?></div>
        <div class="panel-body">
            <div class="col-lg-6">
            <?php 
        	//print_r(yii::$app->db);
        	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
        		'fk_id'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
        					'local'=>app\models\Funct::FK(2),
        					'limit'=>10,
        				]],
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder'=>'Kode Fakultas...'
                        ],
                    ],
        		], 
        		'fk_nama'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
        					'local'=>app\models\Funct::FK(3),
        					'limit'=>10,
        				]],
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder'=>'Nama Fakultas...'
                        ],
                    ],
        		], 
				'fk_head'=>[
					'label'=>'Pimpinan',
					'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
					'options'=>[
						'dataset' => [[
							'local'=>app\models\Funct::DSN(3),
							'limit'=>10,
						]],
						'options' => [
							'fullSpan'=>6,
							'placeholder' => 'Pimpinan',
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



