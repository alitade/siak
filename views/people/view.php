<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\People */

$this->title = $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Peoples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="people-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->No_Registrasi], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->No_Registrasi], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'No_Registrasi',
            'Nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'pekerjaan',
            'kewarganegaraan',
            'status',
            'alamat',
            'kota',
            'kode_pos',
            'propinsi',
            'negara',
            'asal_sekolah',
            'status_sekolah',
            'alamat_sekolah',
            'kode_pos_sekolah',
            'telepon_sekolah',
            'tahun_lulus',
            'nomor_sttb',
            'jurusan_di_sekolah',
            'fakultas',
            'program_studi',
            'nama_ortu_wali',
            'alamat_ortu_wali',
            'kode_pos_ortu_wali',
            'telepon_ortu_wali',
            'pekerjaan_ortu',
            'informasi_usb_ypkp',
            'id_admin',
            'tgl_daftar',
            'status_terima',
            'ket_beasiswa',
            'ket_program',
            'ket_pendapat',
            'Foto',
        ],
    ]) ?>

</div>
