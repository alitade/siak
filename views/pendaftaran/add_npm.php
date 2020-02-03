<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;

use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var app\models\Pendaftaran $model
 */


#Funct::v($listTarif);
$TARIF=[];
$tarif='';
foreach($listTarif as $d){
    $TARIF[$d['id']]=$d['id'];
    $tarif=$d['id'];
}

Modal::begin([
    'header' =>false,
    'id' => 'modals',
    'size'=>'modal-lg',
]);
Modal::end();


$this->title = $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Pendaftarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$alamatC = explode("|",$mBio->alamat_ktp);
$alamatC[1]=' RT '.($alamatC[1]?:'-');
$alamatC[2]=' RW '.($alamatC[2]?:'-');
$alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
$alamatC[4]=' Kec '.($alamatC[4]?:'-');
$alamatC=implode(", ",$alamatC);

$model->id_tarif=$tarif;
?>
<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold">Info Calon Mahasiswa</span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
            No Registrasi: <?= $model->No_Registrasi ?>
        </span>
        <div style="clear: both"></div>
    </div>


    <div class="raw">
        <div class="col-md-2">
            <?php
            $img="no_foto.jpg";
            if($mBio->photo!=''){$img=$mBio->photo;}
            echo Html::img("@web/pt/$img",['class'=>'img-thumbnail'])?>
            <p></p>
        </div>
        <div class="col-md-10">
            <table class="table table-bordered table-condensed">
                <tr><th>No Ktp.</th><td><?= $mBio->no_ktp ?> </td></tr>
                <tr><th>Nama</th><td><?= $mBio->nama ?> </td></tr>
                <tr><th>Alamat</th><td><?= (count(explode("|",$mBio->alamat_ktp))>0?$alamatC:'-' )?> </td></tr>
                <tr><th>Asal Sekolah</th><td><?= $model->asal_sekolah ?> </td></tr>
                <tr><th>Jenjang / Jurusan</th><td><?= $model->jenjang->jenjang ?> / <?= $model->jurusan_di_sekolah ?></td></tr>
                <tr><th>Semester Akhir</th><td><?= ($model->semester_akhir?:'-') ?></td></tr>
            </table>
        </div>
    </div>


    <div class="raw">
        <div class="col-md-6">
            <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                <span style="font-size:14px;font-weight:bold">Info Pendaftaran</span>
                <div class="pull-right"></div>
            </div>
            <p></p>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr><th width="160px">Konsultan Pendaftaran</th><td> <?= $model->prdaftar->konsultan->vendor ?> </td></tr>
                <tr><th>Fakultas</th><td> <?= $model->jr->fk->fk_nama ?> </td></tr>
                <tr><th>Jurusan</th><td> <?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama ?> </td></tr>
                <tr><th>Program</th><td> <?= $model->ket_program ?> </td></tr>
                </thead>

            </table>

        </div>

        <div class="col-md-6">
            <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                <span style="font-size:14px;font-weight:bold"> Form NPM </span>
                <div class="pull-right"></div>
            </div>
            <p></p>
            <?php
            $form= ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL
                ,'action'=>\yii\helpers\Url::to(['save-npm','id'=>$model->No_Registrasi])
            ]);
            ?>

            <?=

            Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    [
                        'label'=>'Tarif',
                        'columns'=>2,
                        'attributes'=>[
                            'id_tarif'=>[

                                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                                'options'=>[

                                    'data' => $TARIF,
                                    'options' => [
                                        'fullSpan'=>6,
                                        'placeholder' => ' -- ',
                                        'value'=>$tarif,
                                        ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ],
                            ],
                            [
                                'type'=> Form::INPUT_RAW,
                                'value'=>Html::a('Cek Tarif',['#'],['class'=>'btn btn-primary','id'=>'popupModal']),
                            ],

                        ],
                    ],
                    'status_pendaftaran'=>[
                        'label'=>'Status','type'=> Form::INPUT_RADIO_LIST,'items'=>[1=>'Baru','Linier','Non Linier'],
                        'options'=>['inline'=>true],],
                    [
                        'label'=>'Semester',
                        'columns'=>2,
                        'attributes'=>[
                            'semester_lanjutan'=>['type'=>Form::INPUT_TEXT],
                            'kurikulum'=>[
                                'type'=>Form::INPUT_TEXT,
                                'options'=>[
                                    'value'=>$kurikulum,
                                ]
                            ],

                        ]
                    ],
                    [
                        'label'=>'NPM',
                        'columns'=>2,
                        'attributes'=>[
                            'npm'=>[
                                'label'=>false,'type'=> Form::INPUT_TEXT,
                                'options'=>['value'=>$gNPM,]
                            ],
                            [
                                'type'=>Form::INPUT_RAW,
                                'value'=>
                                    Html::a("<i></i> Generete NPM ",'javascript:;',['class'=>'btn btn-primary','id'=>'gNPM']).' '
                                    .Html::img(Url::to("@web/images/load.gif"),['width'=>'30px','id'=>'loading-image'])
                            ],

                        ]
                    ],
                    [
                        'type'=>Form::INPUT_RAW,
                        'value'=> Html::submitButton('Save',['class'=>'btn btn-primary btn-sm pull-right']),
                    ],


                ]
            ]);
            ?>
            <?php
            $form->end();
            ?>



        </div>



    </div>
    <div class="clearfix"></div>

    <p></p>
    <?php if($model->npm) {
        $Status=['Baru','Linier','Non Linier'];
        ?>
        <div class="panel-heading">
            <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                <span style="font-size:14px;font-weight:bold">Info Mahasiswa</span>
                <div class="pull-right"></div>
            </div>
            <div style="clear: both"></div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th width="160px"> Jurusan (Program)</th>
                    <td><?= $model->jr->jr_jenjang . ' ' . $model->jr->jr_nama . " (" . $model->pr->pr_nama . ")" ?></td>
                </tr>
                <tr>
                    <th> Semester Awal</th>
                    <td>Semester <?= $model->semester_lanjutan ?></td>
                </tr>
                <tr><th> NPM </th><td><?= $model->npm ?></td></tr>
                <tr><th> Angkatan | Kurikulum </th><td>2017 | <?= $model->kurikulum ?></td></tr>
                <tr><th> Status Pendaftaran </th><td><?= $Status[$model->status_pendaftaran] ?></td></tr>

                <tr>
                    <th> Kode Biaya</th>
                    <td><?= $model->id_tarif ?></td>
                </tr>

            </table>
        </div>
        <?php
    }else{ ?>
        <div class="panel-heading">
            <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                <span style="font-size:14px;font-weight:bold">NPM Mahasiswa ( <?= ($model->npm?:"-") ?> )</span>
                <div class="pull-right"></div>
            </div>
            <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
                Jurusan (Program) : <?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama." (".$model->pr->pr_nama.")" ?>
            </span>
            <div style="clear: both"></div>
        </div>
        <div class="panel-body">
            <div class="col-md-6">

            </div>

        </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php

$this->registerJs("
$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     var idtarif = $('#daftarnpm-id_tarif').val();
     var link    ='".Url::to(['tarif'])."?id='+idtarif;
     $('#modals').modal('show').find('.modal-content').load(link);
   });
});");


$this->registerJs("
$('#loading-image').hide();

$('#gNPM').click(function () {
    var href = $(this);
    $.ajax({
        url : '".Url::to(['/json/npm'])."',
        type: 'POST',
        beforeSend: function() {
          $('#loading-image').show();
        },
        data:{
            s:$('#daftarnpm-semester_lanjutan').val(),
            kr:$('#daftarnpm-kurikulum').val(),
            nr:'".$model->No_Registrasi."'
        },
        success: function(data, textStatus, jqXHR){
            data = jQuery.parseJSON(data);
            if (data.message !='') {
                alert(data.message);
            }
            $('#daftarnpm-npm').val(data.npm);
            $('#loading-image').hide();
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error : ' + textStatus);
        }
    });
   });
", View::POS_END, 'npmFunction');

