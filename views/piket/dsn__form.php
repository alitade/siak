<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DepDrop;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-form">
<div class="panel panel-primary">
    <div class="panel-heading">Dosen Wali</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $ModMhs,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'jr_id'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::JURUSAN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'ds_wali'=>[
            'label'=>'Dosen',
			'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::MHS(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'mhs_nim'=>[
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
						'depends'		=>	['mahasiswa-jr_id'],
						'url' 			=> 	Url::to(['/akademik/dropmhs']),
						'loadingText' 	=> 	'Loading...',
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
            <?= Html::submitButton($ModMhs->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $ModMhs->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
