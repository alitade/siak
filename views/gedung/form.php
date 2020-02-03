<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

?>

<div class="panel">
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_INLINE,
            'formConfig'=>['showErrors'=>true,]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' =>3,
            'attributes' => [
                'nama'=>[
                    'label'=>'Gedung',
                    'type'=> Form::INPUT_TEXT,
                    'options'=>[
                        'placeholder'=>'Nama Gedung','size'=>40
                    ],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Gedung'],],],
                ],
                'lantai'=>[
                    'label'=>false,
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jumlah Lantai'],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Lantai'],],],

                ],
                [
                    'type'=>Form::INPUT_RAW,
                    'value'=>
                        (
                        $model->isNewRecord ?
                            Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Tambah Data'), ['class'=>'btn btn-primary']):
                            Html::submitButton(Yii::t('app', '<i class="fa fa-edit"></i> Simpan Perubahan'), ['class'=>'btn btn-primary'])
                            .' '.Html::a('<i class="fa fa-remove"></i> Batalkan Perubahan',['index'], ['class'=>'btn btn-primary'])
                        )
                ]
            ]


        ]);
        ActiveForm::end();

        ?>
    </div>

</div>
