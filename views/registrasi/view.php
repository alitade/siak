<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Registrasi $model
 */

$this->title = $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Registrasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasi-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'No_Registrasi',
            'Nama',
            'tempat_lahir',
            [
                'attribute'=>'tanggal_lahir',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE
                ]
            ],
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
            [
                'attribute'=>'tahun_lulus',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE
                ]
            ],
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
            [
                'attribute'=>'tgl_daftar',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE
                ]
            ],
            'status_terima',
            'ket_beasiswa',
            'ket_program',
            'ket_pendapat',
            'no_ktp',
            'no_telepon',
            'ibu_kandung',
            'photo',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->No_Registrasi],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
