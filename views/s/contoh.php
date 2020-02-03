<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->ds_nm;
$this->params['breadcrumbs'][] = $this->title;


?>

<?php
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>'//192.168.11.125/dev2/web/s/proses2',
    #'method'=>'post',
    'options'=>[
            #'enctype'=>'enctype="multipart/form-data"',
            #'enctype'=>'application/x-www-form-urlencoded',
    ],
    'formConfig'=>[

    ]

]);
echo Form::widget([
    'formName' => 'c',
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']],
        'program'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Program...']],
        'jenjang'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenjang...']],
        'check'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Check...']],
        'status_beban'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Beban...']],
        'kelas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kelas...']],
        'tahun'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tahun...']],
        'jurusan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jurusan...']],
        'maksimum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Maksimum...']],
        'utama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Utama...']],

    ]

]);

echo Html::submitButton('Save');
ActiveForm::end();
?>
<!--form enctype="application/x-www-form-urlencoded"></form-->