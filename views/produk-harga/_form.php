<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\ProdukHarga $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="produk-harga-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'kode_produk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Produk...']],

            'aktif'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Aktif...']],

            'Rstat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rstat...']],

            'harga'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Harga...']],

            'cuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Cuid...']],

            'uuid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Uuid...']],

            'duid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Duid...']],

            'utgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'dtgl'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
