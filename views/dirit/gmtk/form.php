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
    <div class="panel-heading">Tambah Group Matakuliah</div>
    <div class="panel-body">
    <?php 
	//print_r($model->getErrors());
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\controllers\matakuliah::GMTK(2),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
            ],
		], 
		'nama'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>app\controllers\matakuliah::GMTK(3),
					'limit'=>10,
				]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
            ],
		], 
		'sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'...']], 
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
