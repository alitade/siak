<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="jadwal-form">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		[
			'label'=>'Tahun Akademik',
			'type'=> Form::INPUT_STATIC,
			'staticValue'=>"<b>".$model->bn->kln->kr->kr_kode."</b>"
		],
		[
			'label'=>'Pengajar',
			'type'=> Form::INPUT_STATIC,
			'staticValue'=>"<b> ".$model->bn->ds->ds_nm." : ".$model->bn->mtk_kode."-".$model->bn->mtk->mtk_nama."</b>"
		],
		[
			'label'=>'Kelas',
			'type'=> Form::INPUT_STATIC,
			'staticValue'=>"<b> ".$model->jdwl_kls." : ".$model->bn->mtk_kode."-".$model->bn->mtk->mtk_nama."</b>"
		],
		[
			'label'=>'Jurusan',
			'type'=> Form::INPUT_STATIC,
			'staticValue'=>"<b>".$model->bn->kln->jr->jr_jenjang." ".$model->bn->kln->jr->jr_nama.": ".$model->bn->kln->pr->pr_nama."</b>"
		],
		[
			'label'=>'Jadwal',
			'type'=> Form::INPUT_STATIC,
			'staticValue'=>"<b> ".$model->rg->rg_nama." : ".app\models\Funct::HARI()[$model->jdwl_hari].", $model->jdwl_masuk - $model->jdwl_keluar</b>"
		],
		'rg_uts'=>[
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::RUANG(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Ruang UTS ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'rg_uas'=>[
		
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::RUANG(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Ruang UAS ',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'jdwl_uts'=>[
			'type'=> Form::INPUT_WIDGET, 
			'value'=>date('Y-m-d H:i:s'),
			'widgetClass'=>DateControl::classname(),
			'options'=>['type'=>DateControl::FORMAT_DATETIME]
			
		], 
		'jdwl_uas'=>[
			'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]
		], 
		'jdwl_uts_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
		'jdwl_uas_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
    ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
</div>
