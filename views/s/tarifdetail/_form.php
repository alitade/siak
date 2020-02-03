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

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'idtarif'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Idtarif...']],

            'tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tipe...']],

            'cc'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cc...']],

            'dpp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Dpp...']],

            'sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sks...']],

            'praktek'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Praktek...']],

            'urutan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Urutan...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
