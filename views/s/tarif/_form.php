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

<div class="tarif-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],
            'program'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Program...']],
            'jenjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenjang...']],
            'check'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Check...']],
            'status_beban'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Beban...']],
            'kelas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kelas...']],
            'tahun'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tahun...']],
            'jurusan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jurusan...']],
            'maksimum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Maksimum...']],
            'utama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Utama...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
