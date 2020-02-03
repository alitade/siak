<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Registrasi $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="registrasi-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'No_Registrasi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter No  Registrasi...']],

            'Nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama...']],

            'tempat_lahir'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tempat Lahir...']],

            'jenis_kelamin'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jenis Kelamin...']],

            'agama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Agama...']],

            'kewarganegaraan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kewarganegaraan...']],

            'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']],

            'alamat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Alamat...']],

            'kota'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kota...']],

            'kode_pos'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Pos...']],

            'propinsi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Propinsi...']],

            'negara'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Negara...']],

            'asal_sekolah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Asal Sekolah...']],

            'status_sekolah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Sekolah...']],

            'alamat_sekolah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Alamat Sekolah...']],

            'nomor_sttb'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nomor Sttb...']],

            'fakultas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fakultas...']],

            'program_studi'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Program Studi...']],

            'nama_ortu_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Nama Ortu Wali...']],

            'alamat_ortu_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Alamat Ortu Wali...']],

            'kode_pos_ortu_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Kode Pos Ortu Wali...']],

            'telepon_ortu_wali'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Telepon Ortu Wali...']],

            'status_terima'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Terima...']],

            'ket_beasiswa'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket Beasiswa...']],

            'ket_program'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket Program...']],

            'ket_pendapat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket Pendapat...']],

            'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter No Ktp...']],

            'no_telepon'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter No Telepon...']],

            'ibu_kandung'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ibu Kandung...']],

            'photo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Photo...']],

            'tanggal_lahir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'tahun_lulus'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'tgl_daftar'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

            'pekerjaan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pekerjaan...']],

            'jurusan_di_sekolah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Jurusan Di Sekolah...']],

            'pekerjaan_ortu'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Pekerjaan Ortu...']],

            'informasi_usb_ypkp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Informasi Usb Ypkp...']],

            'id_admin'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Id Admin...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
