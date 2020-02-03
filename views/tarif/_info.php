<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">INFO TARIF</span>
    <div class="pull-right">
    </div>
    <div style="clear: both"></div>
</div>
<p></p>


<?php
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'vendor'=>[
            'label'=>'Vendor',
            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Functdb::vendor(),
                'options' => ['fullSpan'=>6,'placeholder' => 'Konsultan',],
                'pluginOptions' => ['allowClear' => true],
            ],
        ],
        'fk'=>[
            'label'=>'Fakultas',
            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Functdb::fakultas(),
                'options' => ['fullSpan'=>6,'placeholder' => 'fakultas',],
                'pluginOptions' => ['allowClear' => true],
            ],
        ],
        'jr'=>[
            'label'=>'Jurusan',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
            'options'=>[
                'type'=>2,
                'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions' => [
                    'depends'		=>	['biaya-fk'],
                    'url' 			=> 	Url::to(['/json/fkjr']),
                    'loadingText' 	=> 	'Loading...',
                    'params'=>['biaya-vendor']
                ],
            ],
        ],
        'pr'=>[
            'label'=>'Program',
            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Program'],],],
            'options'=>[
                'data' => app\models\Functdb::program()  ,
                'options' => ['fullSpan'=>6,'placeholder' => 'Program',],
                'pluginOptions' => ['allowClear' => true],

            ],
        ],
        [
            'columns'=>2,
            'attributes'=>[
                'thn'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Angkatan'],],],
                ],
                'kurikulum'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kurikulum'],],],
                ],
            ]

        ],
        'jns'=>[
            'label'=>' ',
            'type'=> Form::INPUT_RADIO_LIST,
            'items'=>[0=>'Baru','Linier','Non Linier'],
            'options'=>['inline'=>true],
        ],
        'ket'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],],
    ]
]);
?>

