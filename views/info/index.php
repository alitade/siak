<?php
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;

?>

<div class='col-sm-12'>
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>0],]);
    echo Form::widget([
        'form' => $form,
        'formName'=>'mhs',
        'columns' =>1,
        'attributes' => [
            'n'=>[
                'label'=>'',
                'attributes'=>[
                    'npm' =>['label'=>false,'type'=>Form::INPUT_TEXT,'options'=>['placeholder'=>'Masukan No Registarsi Pendaftaran']],
                    [
                        'label'=>'','type'=>Form::INPUT_RAW,
                        'value'=>Html::submitButton(Yii::t('app', 'Cari Data'),['class' =>'btn btn-primary','style'=>'text-align:right'])
                    ],
                ],
            ],
        ]
    ]);
    ActiveForm::end();
    ?>
    <?php
    if($model){?>
    <table class="table table-bordered">
        <tr><th class="info" colspan="3"> Data Bagian Keuangan</th></tr>
        <tr><th>Nama (NPM)</th><td><?= "$bio[Nama] ($model->mhs_nim)" ?></td><td> </td></tr>
        <tr><th>Registrasi Perwalian Terakhir</th><td><?= $bio['Nama']?></td><td> </td></tr>
        <tr><th class="warning" colspan="3"> *Jika NPM dan Nama Tidak Sesuai Silahkan Malaporkan Perihal Tersebut Kebagian Keuangan USB YPKP </th></tr>
        <tr><th class="info" colspan="3">Data Bagian Pendaftaran</th></tr>
        <tr><th>No. Pendaftaran</th><td><?= $bio['no_registrasi']?></td><td> </td></tr>
        <tr><th>No. KTP.</th><td><?= $bio['no_ktp']?></td><td> </td></tr>
        <tr><th>Alamat</th><td><?= $bio['alamat']?></td><td> </td></tr>
        <tr><th>Tempat & Tanggal Lahir</th><td><?= $bio['tampat_lahir']." ".$bio['tanggal_lahir']?></td><td> </td></tr>
        <tr><th class="info" colspan="3"> *Jika NPM dan Nama Tidak Sesuai Silahkan Malaporkan Perihal Tersebut Kebagian Keuangan USB YPKP </th></tr>
        <tr><th class="info" colspan="3">Data Bagian IT</th></tr>
        <tr>
            <th>FID</th>
            <td><?= ($bio2['Fid']?"<i class='glyphicon glyphicon-ok-circle'></i> Finger ID Sudah Terdaftar":"Silahkan Hubungi Bagian IT Untuk Mendapatkan Finger ID")?></td>
            <td> </td>
        </tr>
    </table>
    <?php
    }else{
        if(!$data):
        ?>
        <div class="alert alert-danger"> <h3>NPM Tidak Terdaftar! Silahkan Hubungi Bagian Keuangan USB YPKP untuk mendapatkan NPM</h3></div>
    <?php endif; } ?>
</div>
