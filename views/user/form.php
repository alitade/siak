<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use app\models\TblTipe;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 * @var yii\widgets\ActiveForm $form
 */
$tmpt=TblTipe::find()->all();
$listData=ArrayHelper::map($tmpt, 'tp_id', 'tp_nama');
?>

<?php
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_INLINE,
    'formConfig'=>[
        'showErrors'=>true,
    ]
]);
echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'']],
        'username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Username ...']],
        'password'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Password ...']],
        [
            'type'=>Form::INPUT_RAW,
            'value'=>Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
        ],
    ]
]);

?>
<?php ActiveForm::end();?>