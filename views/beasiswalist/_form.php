<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswalist $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="beasiswalist-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],

            'jenis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenis...']],

            'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']],

            'jumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jumlah...']],

            'counter'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Counter...']],

            'nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nim...']],

            'tahun'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tahun...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
