<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Berita */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="berita-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="panel panel-primary">
    <div class="panel-heading">Berita</div>
    <div class="panel-body">
        <div class="col-lg-6">

            <?= $form->field($model, 'judul')->textInput() ?>

            <?= $form->field($model, 'isi_berita')->textarea(['rows' => 6]) ?>

        </div>
    </div>
</div>

    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-save"></i> Simpan' : '<i class="glyphicon glyphicon-edit"></i> Simpan Perubahan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
