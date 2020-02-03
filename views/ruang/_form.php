<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ruang-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'rg_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Kode...']], 
		'rg_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rg Nama...']], 
		'kapasitas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kapasitas...']], 

		'IdGedung'=>[
            'Label'=>'Gedung',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => Funct::GEDUNG(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Gedung... ',
                    'multiple' =>false,							
                ],
            ],
		], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
