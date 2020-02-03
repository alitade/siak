<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulKrDet $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="matkul-kr-det-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'kode_kr' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Kode Kr...']],

            'kode' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Kode...']],

            'matkul' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Matkul...']],

            'matkul_en' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Matkul En...']],

            'Rstat' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Rstat...']],

            'cuid' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Cuid...']],

            'sks' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Sks...']],

            'uuid' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Uuid...']],

            'duid' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Duid...']],

            'ctgl' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

            'utgl' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

            'dtgl' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => DateControl::classname(),'options' => ['type' => DateControl::FORMAT_DATETIME]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
