<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Pendaftaran $model
 */

$this->title = $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Pendaftarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pendaftaran-view">
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
            'id',
            'No_Registrasi',
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
