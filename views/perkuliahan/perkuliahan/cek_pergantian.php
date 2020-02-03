<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


$this->title = 'Cek Pergantian Jadwal Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;
$mk="";
$totMhs=0;
$no=0; foreach ($jadwal as $d){
    $n++;
    $totMhs+=$d->_totmhs;
    $mk.=" <span class='badge' style='background:#000' >[".$d->bn->mtk->mtk_kode."] ".$d->bn->mtk->mtk_nama." ($d->jdwl_kls) | $d->_totmhs <i class='fa fa-users'></i></span> ";
}


?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title"><?= $this->title.": ".$model->bn->ds->ds_nm ?></span> </div>
    <div class="panel-body">
        <div class="col-sm-12">
            <h3><?= Funct::HARI()[$model->jdwl_hari].", $model->jdwl_masuk - $model->jdwl_keluar ($totMhs <i class='fa fa-users'></i>)" ?></h3>
            <?= $mk ?>
            <hr>
            <?php $form = ActiveForm::begin(); ?>
            <?php
            echo Form::widget([
                'formName' =>'G',
                'form' => $form,
                'columns' =>4,
                'attributes' => [
                    'sesi'=>[
                        'label'=>'Perkuliahan',
                        'options'=>['placeholder'=>'...'],
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' => $listSesi,
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => ' Perkuliahan',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                    ],

                    'tgl'=>[
                        'label'=>'Tanggal',
                        'type'=> Form::INPUT_WIDGET,
                        'widgetClass'=>DateControl::classname(),
                        'value'=>$_POST['G']['tgl'],
                        'options'=>[
                            'type'=>DateControl::FORMAT_DATE,
                            'displayFormat'=>'php:Y-m-d',
                            'options'=>[
                                'options'=>['required'=>'required']
                            ]
                        ]
                    ],
                    'masuk'=>[
                        'label'=>'Jadwal',
                        'attributes' =>[
                            'masuk'=>[
                                'type'=> Form::INPUT_WIDGET,
                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                'value'=>$_POST['G']['masuk'],
                                'options'=>[
                                    'pluginOptions' => [
                                        'showSeconds' => false,
                                        'showMeridian' => false,
                                        'minuteStep' => 50,
                                    ],
                                    'options'=>['required'=>'required']
                                ]
                            ],
                            'keluar'=>[
                                'type'=> Form::INPUT_WIDGET,
                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                'value'=>$_POST['G']['keluar'],
                                'options'=>[
                                    'pluginOptions' => [
                                        'showSeconds' => false,
                                        'showMeridian' => false,
                                        'minuteStep' => 50,
                                    ],
                                    'options'=>['required'=>'required']
                                ]
                            ],
                        ]
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_STATIC,
                        'staticValue'=>Html::submitButton('Cek Jadwal', ['class' =>'btn btn-primary'])
                    ]
                ]


            ]);
            ?>
            <?php ActiveForm::end(); ?>
        </div>

        <?= ($data?$data:"") ?>

    </div>
</div>
