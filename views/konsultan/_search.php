<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;


?>

<div class="konsultan-search">

    <?php $form = ActiveForm::begin([
        'formConfig' => ['labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL],
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?=
    Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'vendor'=>['type'=>Form::INPUT_TEXT],
            'email'=>['type'=>Form::INPUT_TEXT],
            'tlp'=>['type'=>Form::INPUT_TEXT],
            'pic'=>['type'=>Form::INPUT_TEXT],
            [
                'label'=>'',
                'type'=>Form::INPUT_RAW,
                'value'=>Html::submitButton('Search', ['class' => 'btn btn-primary']).' '.Html::a('Reset',['index'], ['class' => 'btn btn-default'])

            ]

        ]
    ])
    ?>
    <?php ActiveForm::end(); ?>

</div>
