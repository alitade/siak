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

<div class="matkul-form">
<div class="panel panel-primary">
    <div class="panel-heading">User</div>
    <div class="panel-body">
    <?php 
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap ...']],
        'username'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Username ...']],
        'password'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=>'Password ...']],
        'posisi'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
                    'local'=>app\models\Funct::JIL(3),
                    'limit'=>10,
                ]],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Posisi ... ',
                ],
            ],
        ], 
        'tipe'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => $listData,
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],
        ], 
        'akses'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => \app\models\Funct::AKSES(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],
        ], 
    ]
    ]);
    
     ?>
    </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>