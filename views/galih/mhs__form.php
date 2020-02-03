<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="mahasiswa-form">
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Mahasiswa</div>
    	<div class="panel-body">
		    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

		    'model' => $model,
		    'form' => $form,
		    'columns' => 1,
		    'attributes' => [

				'mhs_nim'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nim...']], 

				'mhs_stat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Status...']], 

				'mhs_tipe'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Tipe...']], 

				'mhs_pass'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Password...']], 

				'mhs_pass_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Mhs Pass Kode...']], 

				'mhs_angkatan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Angkatan...']], 

				'jr_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Jurusan...']], 

				'pr_kode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Program...']], 

				'ds_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Dosen Wali...']], 

			]


		    ]);
		    
		    ?>
		</div>
	</div>
	<div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-save"></i> Simpan' : '<i class="glyphicon glyphicon-edit"></i> Simpan Perubahan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>