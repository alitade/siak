<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$tmpt=Ruang::find()->all();
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');
?>

<div class="jadwal-form">
<div class="panel panel-primary">
    <div class="panel-heading">Jadwal Kuliah</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
    	'bn_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'ID Bobot Nilai']], 
    	'semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Semester...']], 
        'jdwl_hari'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$hari], 
            'hint'=>'Pilih Hari'
        ],  
    	'jdwl_masuk'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Masuk']], 
    	'jdwl_keluar'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jam Keluar']], 
        'rg_kode'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang], 
            'hint'=>'Pilih Ruangan'
        ],	
    	'jdwl_kls'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kelas']], 
        'rg_uts'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang], 
            'hint'=>'Pilih Ruangan'
        ],
        'rg_uas'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang], 
            'hint'=>'Pilih Ruangan'
        ],
    	'jdwl_uts'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
    	'jdwl_uas'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
    	'jdwl_uts_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
    	'jdwl_uas_out'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 
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

<?php ActiveForm::end(); ?>
