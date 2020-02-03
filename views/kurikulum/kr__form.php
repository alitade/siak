<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

?>

<div class="kurikulum-form">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Kode...']], 
		'kr_nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kr Nama...']], 

    ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
</div>
