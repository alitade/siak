<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHarga $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="produk-harga-form">
	<fieldset>
    	<legend> Tambah Harga Produk </legend>
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' =>3,
        'attributes' => [
            'harga'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Harga...']],
			'aktif'=>[
				'type'=> Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\SwitchInput',
			],
			[
				'type'=> Form::INPUT_RAW, 
				'value'=>Html::submitButton('Save', ['class' =>'btn btn-primary']),
			]
        ]

    ]);
    ActiveForm::end(); ?>

    </fieldset>
</div>
