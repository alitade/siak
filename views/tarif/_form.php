<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">TARIF</span>
    <div class="pull-right">
    </div>
    <div style="clear: both"></div>
</div>
<p></p>


<?php
$model->aktif=0;
echo Form::widget([
    'model' => $model,
    'form' => $form,
    #'columns' =>2,
    'attributes' => [
        [
            'columns'=>2,
            'label'=>'Tarif',
            'attributes'=>[
                'kode_tarif'=>[
                    #'label'=>'Kode TArif',
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kode Tarif']
                ],
                'total'=>[
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
                'status_beban'=>[
                    #'label'=>'Biaya Termasuk Beban',
                    'type'=> Form::INPUT_RADIO_LIST,
                    'items'=>[1=>'Termasuk Beban',0=>'Tidak Termasuk Beban'],
                    'options'=>['inline'=>true,]
                ],

            ]

        ],
        [
            'columns'=>3,
            'label'=>'Pembayaran',
            'attributes'=>[
                'jenis'=>[
                    #'label'=>'Jenis Tarif',
                    'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' =>['0'=>'SKS','1'=>'Paket'],
                        'options' => ['fullSpan'=>6,'placeholder' => 'Jenis Tarif',],
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                'maksimum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jumlah Termin']],
                'satuan'=>[
                    'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' =>['0'=>'Semester','1'=>'Bulan'],
                        'options' => ['fullSpan'=>6,'placeholder' => 'Satuan',],
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],


            ]

        ],
        'aktif'=>[
            'label'=>'Status',
            'type'=> Form::INPUT_RADIO_LIST,
            'items'=>[1=>'Aktif',0=>'Non Aktif'],
            'options'=>['inline'=>true,]
        ],
        /*'aktif'=>[
            'label'=>false,
            'type'=>Form::INPUT_WIDGET,
            'labelSpan'=>0,
            'widgetClass'=>'\kartik\widgets\SwitchInput',
            'options'=>[
                #'type'=>2,
                'pluginOptions' => [
                    'onText'=>'Aktif',
                    'offText'=>'Non Aktif',
                ],
            ],
        ],*/
    ]
]);


?>
