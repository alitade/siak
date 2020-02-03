<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Ujian */

$this->title = 'Update Ujian: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Ujians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ujian-update">
    <h4><?= $model->jadwal->bn->ds->ds_nm." (".$model->jadwal->bn->mtk->mtk_nama.")" ?></h4>
    <div class="ujian-form">
        <?php 
		$tmpt=Ruang::find()->all();
		$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
		$har=Funct::getHari1();
		$hari=ArrayHelper::map($har, 'id', 'nama');

		$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
		echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
    	'Tgl'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'YYYY-MM-DD (2016-01-31)']], 
    	'Masuk'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'hh:mm (23:59)']], 
    	'Keluar'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'hh:mm (23:59)']], 
        'RgKode'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>['data'=>$ruang], 
            'hint'=>'Pilih Ruangan'
        ],	
    ]


    ]);
		
		
		?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
