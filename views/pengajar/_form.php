<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\BobotNilai $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="bobot-nilai-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kln_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kln ID...']], 
		'nb_tgs1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs1...']], 
		'nb_tgs2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs2...']], 
		'nb_tgs3'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs3...']], 
		'nb_tambahan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tambahan...']], 
		'nb_quis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Quis...']], 
		'nb_uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Uts...']], 
		'nb_uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Uas...']], 
		'mtk_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mtk Kode...']], 
		'ds_nidn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds Nidn...']], 
		'B'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter B...']], 
		'C'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter C...']], 
		'D'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter D...']], 
		'E'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter E...']], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
