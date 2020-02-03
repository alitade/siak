<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


use app\models\Funct;

$this->title = 'Daftar Pengajar vakasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
	:target {
		color: #00C !important;
		background:#000 !important;
		font-weight:bold;
	}
</style>
<div class="bobot-nilai-index">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2], 'method' => 'get',]);
    echo Form::widget([
        'form' => $form,
        'formName'=>'kr',
        'columns' =>2,
        'attributes' => [
            'kr'=>[
                'label'=>false,
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' =>app\models\Funct::AKADEMIK(),
                    'options' => ['fullSpan'=>6,'placeholder' => 'Pilih Tahun Akademik'],
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],
            [
                'label'=>'',
                'type'=>Form::INPUT_RAW,
                'value'=> Html::submitButton(Yii::t('app', 'Cari'),['class' =>'btn btn-primary','style'=>'text-align:right']
                )
            ],
        ]
    ]);
    ActiveForm::end();
	
    ?>
    <?= $tbl ?>
</div>
