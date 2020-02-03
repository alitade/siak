<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var app\models\MahasiswaSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mahasiswa-search">

    <?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' =>2, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action' => ['/matkul-kr/mhs-create','id'=>$IDKR],
        'method' => 'get',
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'pr_kode'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Funct::PROGRAM(),
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'columns'=>2,
                'label'=>'NPM|Nama',
                'attributes'=>[
                    'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'NPM']],
                    'Nama'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama']],
                ],
            ],
            [
                'columns'=>2,
                'label'=>'Tahun|Kurikulum',
                'attributes'=>[
                    'mhs_angkatan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Angkatan...']],
                    'thn'=>['type'=>Form::INPUT_TEXT,'options'=>['placeholder'=>'Kurikulum [11213]']],
                ],
            ],
            'ws'=>[
                'label'=>'Lulus',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => [0=>'Belum Lulus',1=>'Lulus'],
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
