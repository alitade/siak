<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswajenis $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="beasiswajenis-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'namabeasiswa'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Namabeasiswa...']],
            'jenispotongan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenispotongan...']],
            'jumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenispotongan...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
