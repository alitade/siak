<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarifdetail $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="tarifdetail-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
?>

    <?=
    Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'denda'=>[
                'label'=>'Kategori',
                'type'=> Form::INPUT_RADIO_LIST,
                'items'=>[0=>'Denda','1'=>'Wajib'],
                'options'=>['inline'=>true,]
            ],
            [
                'columns'=>2,
                'label'=>'Item',
                'attributes'=>[
                    'item'=>[
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' =>[0=>'DPP Bulanan','DPP Semester','SKS','Matakuliah',],
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => ' Item',
                                'value'=>$tarif,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                    ],
                    /*'dpp[3]'=>[
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' =>[0=>'Semester','Bulan'],
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => ' Item',
                                'value'=>$tarif,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                    ],*/

                ]

            ],
            'dpp'=>[
                'label'=>'Biaya',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\yii\widgets\MaskedInput',
                'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Rp'],],],
                'options'=>[
                    #'width'=>'10%',
                    #'options'=>['placeholder'=>'DPP',],
                    'clientOptions' => [
                        'alias' =>'decimal',
                        'groupSeparator'=>',',
                        'autoGroup' =>true
                    ],
                ],
            ],
        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
