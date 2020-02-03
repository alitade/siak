<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="konsultan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode...']],

            'vendor'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Vendor...']],

            'email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Email...']],

            'tlp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tlp...']],

            'pic'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pic...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
