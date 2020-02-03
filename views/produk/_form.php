<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Produk $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="produk-form">
    <?php 
	$kat=Yii::$app->db->createCommand("select * from produk_kategori")->queryAll();
	$Lkat=[];
	foreach($kat as $d){$Lkat[$d['id']]=$d['kategori'];}
	
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
	echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' =>4,
        'attributes' => [
            'kode'=>['label'=>false,
				'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kode']
			],
            'produk'=>['label'=>false,'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Produk...']],
            'harga'=>['label'=>false,'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Harga Produk...']],
			'kategori'=>['label'=>false,
				//'label'=>'Ketua Jurusan' ,
				'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' =>$Lkat,
					'options' => [
						'fullSpan'=>6,
						'placeholder' => 'kategori',
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],									
			],


			[
				'type'=> Form::INPUT_RAW,
				'value'=>Html::submitButton('Simpan', ['class' =>'btn btn-primary'])
			]
        ]
    ]);
    ActiveForm::end(); ?>
</div>
