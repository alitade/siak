<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Gedung $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="panel panel-primary">
    <div class="panel-heading"><?= $title ?></div>
    <div class="panel-body">

    <?php 
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...']], 
        'Lantai'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Lantai...']], 
        [
            'type'=>Form::INPUT_RAW,
            'value'=>Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
        ]
    ]


    ]);
    ActiveForm::end(); 
    ?>
    </div>
</div>