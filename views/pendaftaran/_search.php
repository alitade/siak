<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\PendaftaranSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="pendaftaran-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'No_Registrasi') ?>

    <?= $form->field($model, 'asal_sekolah') ?>

    <?= $form->field($model, 'status_sekolah') ?>

    <?= $form->field($model, 'alamat_sekolah') ?>

    <?php // echo $form->field($model, 'tahun_lulus') ?>

    <?php // echo $form->field($model, 'nomor_sttb') ?>

    <?php // echo $form->field($model, 'jurusan_di_sekolah') ?>

    <?php // echo $form->field($model, 'fakultas') ?>

    <?php // echo $form->field($model, 'program_studi') ?>

    <?php // echo $form->field($model, 'nama_ortu_wali') ?>

    <?php // echo $form->field($model, 'alamat_ortu_wali') ?>

    <?php // echo $form->field($model, 'kode_pos_ortu_wali') ?>

    <?php // echo $form->field($model, 'telepon_ortu_wali') ?>

    <?php // echo $form->field($model, 'pekerjaan_ortu') ?>

    <?php // echo $form->field($model, 'informasi_usb_ypkp') ?>

    <?php // echo $form->field($model, 'id_admin') ?>

    <?php // echo $form->field($model, 'tgl_daftar') ?>

    <?php // echo $form->field($model, 'status_terima') ?>

    <?php // echo $form->field($model, 'ket_beasiswa') ?>

    <?php // echo $form->field($model, 'ket_program') ?>

    <?php // echo $form->field($model, 'ket_pendapat') ?>

    <?php // echo $form->field($model, 'no_ktp') ?>

    <?php // echo $form->field($model, 'no_telepon') ?>

    <?php // echo $form->field($model, 'ibu_kandung') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
