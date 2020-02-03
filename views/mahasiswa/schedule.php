<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use app\models\Funct;
/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,
        'method'=>'get',
        'action' => Url::to(['mahasiswa/schedule-detail'])
        ]); 

    echo $form->field($model, 'jenis')
        ->dropDownList(
            ['empty'=>'Jenis', 'kuliah'=>'Jadwal Kuliah', 'UTS'=>'Jadwal UTS', 'UAS'=>'Jadwal UAS']
        );

    echo $form->field($model, 'kr_kode')
        ->dropDownList(
            Funct::Kalender()
        );

    echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Cari'), ['class' => 'btn btn-primary']);
    ActiveForm::end(); ?>

