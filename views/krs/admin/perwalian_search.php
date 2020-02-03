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
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        #'action' =>['perwalian','perwalian[kr]'=>$kr],
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
            'Nama'=>['type'=>Form::INPUT_TEXT,],
            'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nim...']],
            'mhs_angkatan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Angkatan...']],
            'thn'=>['type'=>Form::INPUT_TEXT,'label'=>'Kurikulum'],
            'reg'=>[
                'label'=>'Registrasi',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => [0=>'N',1=>'Y'],
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            'krs'=>[
                'label'=>'KRS',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => [0=>'N',1=>'Y'],
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            'krsHeadApp'=>[
                'label'=>'Persetujuan',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => [0=>'N',1=>'Y'],
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            'tipe'=>[
                'label'=>'Ket.',
                'type'=>Form::INPUT_RADIO_LIST,
                'items'=>[null=>'Tampilkan Semua',1=>'SMA/SMK/SEDERAJAT',2=>'Mahasiswa Melanjutkan']
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
