<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

?>

<div class="hari-ini-search">

    <?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action' => ['/perkuliahan/berjalan'],
        'method' => 'get',
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'jdwl_id'=>['type'=>Form::INPUT_TEXT],
            'mtk_kode'=>[
                'label'=>'Matakuliah/Dosen',
                'type'=>Form::INPUT_TEXT
            ],
            'jdwl_masuk'=>[
                'label'=>'Jams',
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\widgets\TimePicker',
                'options'=>[
                    'pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                        'minuteStep' => 50,
                    ],
                    'readonly'=>($B?true:false)
                ]
            ],
            [
                'label'=>'',
                'type'=>Form::INPUT_RAW,
                'value'=>Html::submitButton('Search', ['class' => 'btn btn-primary']).' '.HTML::a('Reset',['berjalan'],['class'=>'btn btn-defautl'])
            ]
        ]
    ])
    ?>
    <?php ActiveForm::end(); ?>

</div>
