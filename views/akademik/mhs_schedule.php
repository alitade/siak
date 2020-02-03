<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Jadwal Ujian';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="jadwal-form">

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,
        'action' => Url::to(['mahasiswa/schedule-detail'])
        ]); 

  //  $dataList=ArrayHelper::map(Course::find()->asArray()->all(), 'id', 'name');
    echo $form->field($model, 'jenis')
        ->dropDownList(
            //$dataList,           // Flat array ('id'=>'label')
            ['empty'=>'Jenis','UTS'=>'UTS', 'UAS'=>'UAS']
            //['prompt'=>'']    // options
        );

    echo $form->field($model, 'kr_kode')
        ->dropDownList(
            //$dataList,           // Flat array ('id'=>'label')
            ['empty'=>'Jenis','UTS'=>'UTS', 'UAS'=>'UAS']
            //['prompt'=>'']    // options
        );

    echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
