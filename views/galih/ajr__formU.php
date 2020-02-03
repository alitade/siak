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
<div class="panel panel-primary">
    <div class="panel-heading">Dosen Pengajar</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kln_id'=>[
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->kr_kode,
			'staticOnly'=>TRUE
			
		], 

		'jurusan'=>[
			'label'=>'Jurusan',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->jr->jr_id.": ".$model->kln->jr->jr_jenjang." ".$model->kln->jr->jr_nama,
		], 

		'program'=>[
			'label'=>'Program',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->pr->pr_nama,
		], 

		'mtk_kode'=>[
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::MTK(1,['jr_id'=>$model->kln->jr_id]),
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
?>
    </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php 
//var_dump($model);die();
 /*
	$this->registerJs(
		"jQuery('#bobotnilai-kalender').val($model->kr_kode).change();
		 
		",
		yii\web\View::POS_END, 'set_kalender');*/
 ?>