<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\RegistrasiSearch $searchModel
 */

$this->title = 'Registrasis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasi-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Registrasi', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'No_Registrasi',
            'Nama',
            'tempat_lahir',
            ['attribute'=>'tanggal_lahir','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            'jenis_kelamin',
//            'agama', 
//            'pekerjaan', 
//            'kewarganegaraan', 
//            'status', 
//            'alamat', 
//            'kota', 
//            'kode_pos', 
//            'propinsi', 
//            'negara', 
//            'asal_sekolah', 
//            'status_sekolah', 
//            'alamat_sekolah', 
//            ['attribute'=>'tahun_lulus','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 
//            'nomor_sttb', 
//            'jurusan_di_sekolah', 
//            'fakultas', 
//            'program_studi', 
//            'nama_ortu_wali', 
//            'alamat_ortu_wali', 
//            'kode_pos_ortu_wali', 
//            'telepon_ortu_wali', 
//            'pekerjaan_ortu', 
//            'informasi_usb_ypkp', 
//            'id_admin', 
//            ['attribute'=>'tgl_daftar','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 
//            'status_terima', 
//            'ket_beasiswa', 
//            'ket_program', 
//            'ket_pendapat', 
//            'no_ktp', 
//            'no_telepon', 
//            'ibu_kandung', 
//            'photo', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['registrasi/view','id' => $model->No_Registrasi,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
