<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\DosenWali $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?php
$model->aktif=true;
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'jr_id'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                #'data' => app\models\Functdb::jurusan_plh(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Pilih Program Studi',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],
        ],
        [
            'columns'=>3,
            'label'=>'Dosen',
            #'hint'=>'Hanya Dosen yang memiliki Homebase yang bisa didaftarkan sebagai dosen wali',
            'attributes'=>[
                'ds_id'=>[
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

                    /*'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'columnOptions'=>['colspan'=>2],
                    'options'=>[
                        'type'=>2,
                        'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                        'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions' => [
                            'depends'		=>	['dosenwali-jr_id'],
                            'url' 			=> 	\yii\helpers\Url::to(['/json/dosen','t'=>'h']),
                            'loadingText' 	=> 	'Loading...',
                            'placeholder'=>'Nama Dosen (Daftar Berdasarkan Homebase yang Dipilih)',
                            'params'=>['pendaftaran-id_konsultan']
                        ],
                    ],*/
                ],
                'aktif'=>[
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>'\kartik\widgets\SwitchInput',
                    'options'=>[
                        'pluginOptions' => [
                            'type'=>2 ,
                            'onText'=>'Aktif',
                            'offText'=>'Non Aktif',
                        ],
                    ],
                ],
            ],
        ],
        [
            'type'=>Form::INPUT_RAW,
            'value'=>'<div class="pull-right">'.Html::submitButton('<i class="fa fa-save"></i> Simpan Data',['class'=>'btn btn-success']).'</div>'

        ]

    ]

]);
ActiveForm::end(); ?>
<p class="clearfix"></p>
<!-- footer style="border-top: solid 1px #000">Hanya dosen yang memiliki homebase yang bisa didaftarkan sebagai dosen wali</footer -->


