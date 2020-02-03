<?php


use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'Input Data Pendaftaran Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Pendaftaran Mahasiswa Baru', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
?>
<div class="panel panel-default">
    <div class="panel-heading"><span class="panel-title"> Input Data Calon Mahasiswa </span></div>
    <div class="panel-body">

    <?= $this->render('_formCalon', ['model' => $mBio,'form' => $form]) ?>
    <?= ""#$this->render('_form', ['model' => $model,'mBio' => $mBio,'mWali' => $mWali,]) ?>
    <?= $this->render('_formDaftar', ['model' => $model,'form' => $form]) ?>
    <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        ActiveForm::end();
    ?>
    </div>
</div>
