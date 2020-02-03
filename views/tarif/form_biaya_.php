<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 */

$this->title = 'Tambah Data Tarif';
$this->params['breadcrumbs'][] = ['label' => 'Tarif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Tambah Tarif Baru</span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">

        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'action'=>Url::to("#")
            #'formConfig' => ['labelSpan' => 2,]
        ]);


        echo
        Form::widget([
            'model'=>$mTarif,
            'form'=>$form,
//            'attributeDefaults'=>[
//                'labelOptions'=>['class'=>'col-md-2','style'=>'margin:2px'],
//                'inputContainer'=>['class'=>'col-md-9','style'=>'margin:2px'],
//                'container'=>['class'=>'raw form-group'],
//            ],
            'columns'=>1,
            'attributes'=>[
                [
                    'columns'=>3,
                    'label'=>'Tarif',
                    'attributes'=>[
                        'dpp'=>[
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\yii\widgets\MaskedInput',
                            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Rp'],],],
                            'options'=>[
                                'clientOptions' => [
                                    'alias' =>'decimal',
                                    'groupSeparator'=>',',
                                    'autoGroup' =>true
                                ],
                            ],
                        ],
                        'urutan'=>[
                            'type'=>Form::INPUT_TEXT,
                            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Ke-'],],],
                        ],


                    ]

                ],
                'tipe'=>[
                    'label'=>'Jenis Pembayaran',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[0=>'Denda','Wajib']
                ],
            ]
        ]);


        echo"<br><br><br>";

        /*
        echo
        Form::widget([
            'model'=>$mTarif,
            'form'=>$form,
            'columns'=>1,
            'attributes'=>[
                'kode'=>[
                    'label'=>'Kode Tarif',
                    'type'=>Form::INPUT_TEXT,
                ]
            ]
        ]);
        */

        /* echo Form::widget([
            'model' => $model,
            'form' =>$form,
            'columns' => 1,
            'attributes' =>[
                'vendor'=>[
                    'label'=>'Vendor',
                    'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => app\models\Functdb::vendor(),
                        'options' => ['fullSpan'=>6,'placeholder' => 'Konsultan',],
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                'pr'=>[
                    'label'=>'Program',
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'options'=>[
                        'type'=>2,
                        'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                        'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions' => [
                            'depends'		=>	['biaya-vendor'],
                            'url' 			=> 	Url::to(['/json/get-pr']),
                            'loadingText' 	=> 	'Loading...',
                        ],
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
                            'depends'		=>	['biaya-pr'],
                            'url' 			=> 	Url::to(['/json/pr-jr']),
                            'loadingText' 	=> 	'Loading...',
                            'params'=>['input-type-1', 'input-type-2']
                        ],
                    ],
                ],
                'thn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'']],
                'tot'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\yii\widgets\MaskedInput',
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Rp'],],],
                    'options'=>[
                        #'width'=>'10%',

                        'clientOptions' => [
                            'alias' =>'decimal',
                            'groupSeparator'=>',',
                            'autoGroup' =>true
                        ],
                    ],
                ],
                'ket'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                ],
                [
                    'label'=>'Kode',
                    'columns'=>6,
                    'attributes'=>[
                        'kDvendor'=>['type'=>Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'VN'],],],],
                        'kDfk'=>['type'=>Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'FK'],],],],
                        'kDjr'=>['type'=>Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'JR'],],],],
                        'kDpr'=>['type'=>Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'PR'],],],],
                        'kDthn'=>['type'=>Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'TH'],],],],
                    ],
                ],
                [
                    'type'=>Form::INPUT_RAW,
                    'value'=>Html::submitButton('<i></i> Simpan',['class'=>'btn btn-success pull-right'])
                ],
            ]
        ]);
        */
        ActiveForm::end()
        ?>

        <?php

        ?>

    </div>

    <?php if(isset($_SESSION['tarif'])): ?>
        <div class="panel-heading">
            <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                <span style="font-size:14px;font-weight:bold">Tarif Baru</span>
                <div class="pull-right"></div>
            </div>
            <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">

        </span>
            <div style="clear: both"></div>
        </div>
        <div class="panel-bodi">

        </div>
    <?php endif; ?>


</div>

<?php
$this->registerJs('
    $(document).ready(
        function(){
            $("#biaya-vendor").change(function(){$("#biaya-kdvendor").val($("#biaya-vendor").val());});                
        }
    );

');
