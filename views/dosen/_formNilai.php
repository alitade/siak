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

$this->registerJs(
   '$("document").ready(function(){ 
        $("#edit_bobot").on("pjax:end", function() {
            $.pjax.reload({container:"#BobotNilai"});  //Reload GridView
        });
    });'
);

?>

<div class="bobot-nilai-form">
<?php yii\widgets\Pjax::begin(['id' => 'edit_bobot']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ],'type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributeDefaults'=>[
        'type'=>Form::INPUT_TEXT,
        //'labelOptions'=>['class'=>'col-md-3'], 
        'inputContainer'=>['class'=>'col-md-3'], 
        'container'=>['class'=>'form-group'],
    ],
    'attributes' => [
		'B'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter B...','class'=> 'col-md-3']], 
		'C'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter C...']], 
		'D'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter D...']], 
		'E'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter E...']], 
		'kln_id'=>['label'=> false, 'type'=> Form::INPUT_HIDDEN, 'options'=>['placeholder'=>'Enter Kln ID...']], 
		/*'nb_tgs1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs1...']], 
		'nb_tgs2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs2...']], 
		'nb_tgs3'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tgs3...']], 
		'nb_tambahan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Tambahan...']], 
		'nb_quis'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Quis...']], 
		'nb_uts'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Uts...']], 
		'nb_uas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nb Uas...']], */
		'mtk_kode'=>['label'=> false,'type'=> Form::INPUT_HIDDEN, 'options'=>['placeholder'=>'Enter Mtk Kode...']], 
		'ds_nidn'=>['label'=> false,'type'=> Form::INPUT_HIDDEN, 'options'=>['placeholder'=>'Enter Ds Nidn...']], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end() ?>

</div>
