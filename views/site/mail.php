<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fakultas */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Mail';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fakultas-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Fakultas</div>
    <div class="panel-body">
        <div class="col-lg-6">
    		<?= $form->field($model, 'nama_fakultas')->textInput() ?>
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
