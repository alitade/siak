<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PeopleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peoples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="people-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create People', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'No_Registrasi',
            'Nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            // 'agama',
            // 'pekerjaan',
            // 'kewarganegaraan',
            // 'status',
            // 'alamat',
            // 'kota',
            // 'kode_pos',
            // 'propinsi',
            // 'negara',
            // 'asal_sekolah',
            // 'status_sekolah',
            // 'alamat_sekolah',
            // 'kode_pos_sekolah',
            // 'telepon_sekolah',
            // 'tahun_lulus',
            // 'nomor_sttb',
            // 'jurusan_di_sekolah',
            // 'fakultas',
            // 'program_studi',
            // 'nama_ortu_wali',
            // 'alamat_ortu_wali',
            // 'kode_pos_ortu_wali',
            // 'telepon_ortu_wali',
            // 'pekerjaan_ortu',
            // 'informasi_usb_ypkp',
            // 'id_admin',
            // 'tgl_daftar',
            // 'status_terima',
            // 'ket_beasiswa',
            // 'ket_program',
            // 'ket_pendapat',
            // 'Foto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
