<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\DosenTipe $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dosen-tipe-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tipe...']],
            'maxsks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Maxsks...']],
        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
