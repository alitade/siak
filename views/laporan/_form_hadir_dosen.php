<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


$form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' =>2, 'deviceSize' => ActiveForm::SIZE_SMALL],
        ]
);
echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'kr_kode'=>[
                'label'=>'Kurikulum ',
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\widgets\Select2',
                'value'=>'',
                'options'=>[
                    'data' =>Funct::AKADEMIK(),
                    'options' => ['placeholder' =>'---',],
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],
            'tipe'=>['label'=>'Kelas','type'=>Form::INPUT_DROPDOWN_LIST,'items'=>[0=>'Pagi','Sore']],

            [
                'columns'=>2,
                'label'=>'Periode',
                'attributes'=>[
                    'tgl_awal'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],
                    'tgl_akhir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],
                ]
            ],
            [
                'type'=>Form::INPUT_RAW,
                'value'=>'<div class="pull-right">'.
                    Html::submitButton('<i class="fa fa-eye"></i> Pratinjau', ['class' =>'btn btn-primary','name'=>'pr'])
                    .' '.Html::submitButton('<i class="fa fa-download"></i> Simpan & Unduh Data', ['class' =>'btn btn-primary','name'=>'ex'])
                .'</div>'
            ]

        ]
    ]
);

ActiveForm::end();

?>


