<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;

use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
/**
 * @var yii\web\View $this
 * @var app\models\Pendaftaran $model
 */

$this->title = $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Pendaftarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$btnReg =$model->No_Registrasi;
if($model->No_Registrasi==''){
    $btnReg =Html::a(" Registrasi Mahasiswa ",['regis','id'=>$model->kd_daftar],['class'=>'btn btn-success']);

}
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>"Calon Mahasiswa $model->program_studi ".$model->jr->jr_jenjang." ".$model->jr->jr_nama." (".($model->pr_kode?$model->pr->pr_nama:$model->ket_program).")",
        'before'=>'dsa',
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'No_Registrasi',
            'format'=>'raw',
            'value'=>$btnReg,
        ],
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
        'informasi_usb_ypkp',
        'ket_beasiswa',
        'ket_pendapat',
    ],
    'enableEditMode'=>false,
]) ?>


<div class="clearfix"></div>

<!-- MHS -->
<div class="row">
<div class="col-sm-2">
    <?php
        $img="no_foto.jpg";
        if($mBio->photo!=''){$img=$mBio->photo;}
    ?>

    <?= Html::img("@web/pt/$img",['class'=>'img-thumbnail'])?>
    <p></p>
    <?php
    Modal::begin([
        'header'=>'File Input inside Modal',
        'toggleButton' => [
            'label'=>'<i class="fa fa-upload"></i> Upload', 'class'=>'btn btn-default pull-right'
        ],
    ]);
    $form1 = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data',]
        , 'action'=>\yii\helpers\Url::to(['upload','id'=>$model->kd_daftar]),
        // important
    ]);

    echo $form1->field($mBio, 'photo')->widget(FileInput::classname(), [
        'options' => ['multiple' => true, 'accept' => 'image/*'],
        'pluginOptions' => [
            #'maxFileSize'=>256,
            'overwriteInitial'=>false
        ]
    ]);
    ActiveForm::end();
    Modal::end();

    ?>
</div>

<div class="col-sm-10">
<?php

$alamatC = explode("|",$mBio->alamat_ktp);
$alamatC[1]=' RT '.($alamatC[1]?:'-');
$alamatC[2]=' RW '.($alamatC[2]?:'-');
$alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
$alamatC[4]=' Kec '.($alamatC[4]?:'-');
$alamatC=implode(", ",$alamatC);

$alamatCt = explode("|",$mBio->alamat_tinggal);
$alamatCt[1]=' RT '.($alamatCt[1]?:'-');
$alamatCt[2]=' RW '.($alamatCt[2]?:'-');
$alamatCt[3]=' Kel/Des '.($alamatCt[3]?:'-');
$alamatCt[4]=' Kec '.($alamatCt[4]?:'-');
$alamatCt=implode(", ",$alamatCt);

echo DetailView::widget([
    'model' => $mBio,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Biodata Mahasiswa',
        'type'=>DetailView::TYPE_INFO,

    ],
    'attributes' => [
        'no_ktp',
        'nama',
        [
            'attribute'=>'tempat_lahir',
            'label'=>'Tempat & Tanggal Lahir',
            'format'=>'raw',
            'value'=>$mBio->tempat_lahir.' '.Funct::TANGGAL($mBio->tanggal_lahir)
        ],
        [
            'attribute'=>'jk',
            'label'=>'Jenis Kelamin',
            'value'=>$mBio->jk==1?'Laki-Laki':'Perempuan',
        ],
        'agama',
        'status_ktp',
        'pekerjaan',
        'kewarganegaraan',
        'ibu_kandung',
        [
            'attribute'=>'alamat_ktp',
            'label'=>'Alamat KTP',
            'value'=>$alamatC.". Kode Pos: ".$mBio->kode_pos.', '.$mBio->propinsi
        ],
        [
            'attribute'=>'alamat_tinggal',
            'label'=>'Alamat Tinggal',
            'value'=>$alamatCt.". Kode Pos: ".$mBio->kode_pos_tinggal
        ],
        'tlp','email'
        #'photo',
    ],
    #'container' => ['class'=>'col-sm-6'],
    'enableEditMode'=>false,
])
?>
</div>
</div>
<div class="clearfix"></div>

<!-- Wali -->

<?php
if($mWali){
    $alamatW = explode("|",$mWali->alamat_ktp);
    $alamatW[1]=' RT '.($alamatW[1]?:'-');
    $alamatW[2]=' RW '.($alamatW[2]?:'-');
    $alamatW[3]=' Kel/Des '.($alamatW[3]?:'-');
    $alamatW[4]=' Kec '.($alamatW[4]?:'-');
    $alamatW=implode(", ",$alamatW);

    $alamatWt = explode("|",$mWali->alamat_tinggal);
    $alamatWt[1]=' RT '.($alamatWt[1]?:'-');
    $alamatWt[2]=' RW '.($alamatWt[2]?:'-');
    $alamatWt[3]=' Kel/Des '.($alamatWt[3]?:'-');
    $alamatWt[4]=' Kec '.($alamatWt[4]?:'-');
    $alamatWt=implode(", ",$alamatWt);

    echo DetailView::widget([
        'model' => $mWali,
        'condensed'=>false,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Biodata Wali Mahasiswa',
            'type'=>DetailView::TYPE_INFO,

        ],
        'attributes' => [
            'no_ktp',
            'nama',
            [
                'attribute'=>'tempat_lahir',
                'label'=>'Tempat & Tanggal Lahir',
                'format'=>'raw',
                'value'=>$mWali->tempat_lahir.' '.($mWali->tanggal_lahir?Funct::TANGGAL($mWali->tanggal_lahir):"")
            ],
            [
                'attribute'=>'jk',
                'label'=>'Jenis Kelamin',
                'value'=>$mWali->jk==1?'Laki-Laki':'Perempuan',
            ],
            'agama',
            'status_ktp',
            'pekerjaan',
            'kewarganegaraan',
            [
                'attribute'=>'alamat_ktp',
                'label'=>'Alamat KTP',
                'value'=>$alamatW.". Kode Pos: ".$mWali->kode_pos.', '.$mWali->propinsi
            ],
            [
                'attribute'=>'alamat_tinggal',
                'label'=>'Alamat Tinggal',
                'value'=>$alamatWt.". Kode Pos: ".$mWali->kode_pos_tinggal
            ],
            'tlp','email'
            #'photo',
        ],
        #'container' => ['class'=>'col-sm-6'],
        'enableEditMode'=>false,
    ]);

}

?>
<div class="clearfix"></div>
