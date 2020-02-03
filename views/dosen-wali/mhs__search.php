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
        'formConfig' => ['labelSpan' =>3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action' => $URL?:['/dosen-wali/mhs-create','id'=>$DSID->jr_id,'id1'=>$DSID->ds_id],
        'method' => 'get',
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'pr_kode'=>[
                'label'=>'Program Perkuliahan',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Funct::PROGRAM(),
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            /*[
                'columns'=>3,
                'attributes'=>[
                    'wali'=>[
                        'label'=>'Memiliki Dosen Wali',
                        'type'=>Form::INPUT_CHECKBOX,
                        'value'=>'1',
                    ],
                    'ds_wali'=>[

                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'columnOptions'=>['colspan'=>2],
                        'options'=>[
                            'data' => app\models\Funct::PROGRAM(),
                            'options' => [
                                'fullSpan'=>6,'placeholder' => '... ',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],

                        ],

                    ]

                ]

            ],*/
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
            'tipe'=>[
                'label'=>'Ket.',
                'type'=>Form::INPUT_RADIO_LIST,
                'items'=>[null=>'Tampilkan Semua',1=>'Mahasiswa Baru',2=>'Mahasiswa Melanjutkan']
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
