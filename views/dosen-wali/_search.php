<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var app\models\DosenWaliSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-wali-search">

    <?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action' => ['index'],
        'method' => 'get',
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'jr_id'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            'dosen'=>['type'=>Form::INPUT_TEXT,],
            'aktif'=>[
                #'label'=>'Aktif',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => [0=>'Aktif',1=>'Non Aktif'],
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'label'=>'',
                'type'=>Form::INPUT_RAW,
                'value'=>Html::submitButton('Search', ['class' => 'btn btn-primary']).' '.Html::resetButton('Reset', ['class' => 'btn btn-default'])

            ]
        ]
    ]);

    ?>
    <?php ActiveForm::end(); ?>

</div>
