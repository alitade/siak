<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\People */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="people-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'No_Registrasi')->textInput() ?>

    <?= $form->field($model, 'Nama')->textInput() ?>

    <?= $form->field($model, 'tempat_lahir')->textInput() ?>

    <?= $form->field($model, 'tanggal_lahir')->textInput() ?>

    <?= $form->field($model, 'jenis_kelamin')->textInput() ?>

    <?= $form->field($model, 'agama')->textInput() ?>

    <?= $form->field($model, 'pekerjaan')->textInput() ?>

    <?= $form->field($model, 'kewarganegaraan')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'alamat')->textInput() ?>

    <?= $form->field($model, 'kota')->textInput() ?>

    <?= $form->field($model, 'kode_pos')->textInput() ?>

    <?= $form->field($model, 'propinsi')->textInput() ?>

    <?= $form->field($model, 'negara')->textInput() ?>

    <?= $form->field($model, 'asal_sekolah')->textInput() ?>

    <?= $form->field($model, 'status_sekolah')->textInput() ?>

    <?= $form->field($model, 'alamat_sekolah')->textInput() ?>

    <?= $form->field($model, 'kode_pos_sekolah')->textInput() ?>

    <?= $form->field($model, 'telepon_sekolah')->textInput() ?>

    <?= $form->field($model, 'tahun_lulus')->textInput() ?>

    <?= $form->field($model, 'nomor_sttb')->textInput() ?>

    <?= $form->field($model, 'jurusan_di_sekolah')->textInput() ?>

    <?= $form->field($model, 'fakultas')->textInput() ?>

    <?= $form->field($model, 'program_studi')->textInput() ?>

    <?= $form->field($model, 'nama_ortu_wali')->textInput() ?>

    <?= $form->field($model, 'alamat_ortu_wali')->textInput() ?>

    <?= $form->field($model, 'kode_pos_ortu_wali')->textInput() ?>

    <?= $form->field($model, 'telepon_ortu_wali')->textInput() ?>

    <?= $form->field($model, 'pekerjaan_ortu')->textInput() ?>

    <?= $form->field($model, 'informasi_usb_ypkp')->textInput() ?>

    <?= $form->field($model, 'id_admin')->textInput() ?>

    <?= $form->field($model, 'tgl_daftar')->textInput() ?>

    <?= $form->field($model, 'status_terima')->textInput() ?>

    <?= $form->field($model, 'ket_beasiswa')->textInput() ?>

    <?= $form->field($model, 'ket_program')->textInput() ?>

    <?= $form->field($model, 'ket_pendapat')->textInput() ?>

    <?= $form->field($model, 'Foto')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
