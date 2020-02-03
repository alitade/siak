<?php
$this->title = "Profile";
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Funct;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

$linkImg=Url::to('@web/pt/no_foto.jpg');
if(file_exists("../web/pt/".$modTmp->photo) && $modTmp->photo){$linkImg=Url::to("@web/pt/".$modTmp->photo);}

$ctgl=explode(" ",$modTmp->ctgl);
?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title"> Update Biodata <?= $modTmp->status_data==1?"(Menunggu Konfirmasi Admin)":"" ?></span>
        </div>

        <div class="panel-body">
            <div class="row">
                    <?php if($modTmp->status_data==1){ ?>
                        <div class="col-sm-2 col-md-2">
                            <?= Html::img($linkImg,['class'=>'img img-thumbnail']) ?>
                        </div>
                        <div class="col-sm-10 col-md-10">
                            <table class="table table-bordered">
                                <tr><th width="200">Nama</th><td><?= $model->nama ?> </td></tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>
                                        <?php
                                        $jk="-";
                                        if($modTmp->jk=='1'||$modTmp->jk=='L'){$jk="Laki-Laki";}
                                        if($modTmp->jk=='0'||$modTmp->jk=='P'){$jk="Perempuan";}
                                        echo $jk;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td><?= $modTmp->agm->agama ?> </td>
                                </tr>
                                <tr><th>Tempat & Tanggal Lahir</th><td><?= $modTmp->tempat_lahir.','.Funct::TANGGAL($modTmp->tanggal_lahir,2)." <br><b>".Funct::USIA(@$modTmp->tanggal_lahir)."</b>" ?></td></tr>
                                <tr>
                                    <th>Alamat KTP</th>
                                    <td>
                                        <?php
                                        $alamatC = explode("|",$modTmp->alamat_ktp);
                                        $alamatC[1]=' RT '.($alamatC[1]?:'-');
                                        $alamatC[2]=' RW '.($alamatC[2]?:'-');
                                        $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                                        $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                                        echo implode(", ",$alamatC);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Alamat Tinggal</th>
                                    <td>
                                        <?php
                                        $alamatC = explode("|",$modTmp->alamat_tinggal);
                                        $alamatC[1]=' RT '.($alamatC[1]?:'-');
                                        $alamatC[2]=' RW '.($alamatC[2]?:'-');
                                        $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                                        $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                                        echo implode(", ",$alamatC);
                                        ?>
                                    </td>
                                </tr>
                                <tr><th>No.Tlp.</th><td><?= $modTmp->tlp ?></td></tr>
                                <tr><th>Email</th><td><?= Yii::$app->formatter->asEmail($modTmp->email)  ?></td></tr>
                            </table>
                        </div>
                    <?php }else{
                        echo '<div class="col-sm-12 col-md-12">';
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'options'=>['enctype'=>'multipart/form-data',]
                        ]);
                        ?>
                        <?=
                        Form::widget([
                            'model' => $modTmp,
                            'form' => $form,
                            'columns' => 1,
                            'attributes' => [
                                'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No KTP']],
                                'nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap']],
                                'photo'=>[
                                    'type'=> Form::INPUT_WIDGET,
                                    'widgetClass'=>FileInput::classname(),
                                    'options' => [
                                        'options'=>['multiple' => false, 'accept' => 'image/*'],
                                        'pluginOptions' => [
                                            #'showPreview' => true,
                                            'showUpload' => false,
                                            'overwriteInitial'=>false,
                                            'initialPreview'=>'<div class="col-sm-4 col-md-4">'.Html::img($linkImg,['class'=>'img img-thumbnail']).'</div>',
                                            'initialPreviewAsData'=>true,
                                            'initialPreviewFileType'=>'image',
                                            'overwriteInitial'=>true,
                                        ]
                                    ],
                                ],
                                [
                                    'label'=>'Tempat & Tanggal Lahir',
                                    'columns'=>2,
                                    'attributes'=>[
                                        'tempat_lahir'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tempat'],],],],
                                        'tanggal_lahir'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],
                                    ]

                                ],
                                'agama'=>[
                                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                                    'options'=>[
                                        'dataset' => [[
                                            'local'=>app\models\Functdb::agama_dft(1)?:[''],
                                            'limit'=>10,
                                        ]],
                                        'options' => ['fullSpan'=>6,'placeholder' => 'Agama',],
                                    ],
                                ],
                                'jk'=>['label'=>'Jenis Kelamin','type'=> Form::INPUT_RADIO_LIST,
                                    'items'=>['0'=>'Perempuan','1'=>'Laki-Laki'],
                                    'options'=>['inline'=>true]
                                ],
                                'status_ktp'=>[
                                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                                    'options'=>[
                                        'dataset' => [[
                                            'local'=>app\models\Functdb::statKTP(1)?:[''],
                                            'limit'=>10,
                                        ]],
                                        'options' => ['fullSpan'=>6,'placeholder' => 'Status Pernikahan',],
                                    ],
                                ],
                                'pekerjaan'=>[
                                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                                    'options'=>[
                                        'dataset' => [[
                                            'local'=>app\models\Functdb::kerja_dft(1)?:[''],
                                            'limit'=>10,
                                        ]],
                                        'options' => ['fullSpan'=>6,'placeholder' => 'Pekerjaan',],
                                    ],
                                ],
                                'add'=>['label'=>'Alamat KTP','type'=> Form::INPUT_TEXT,'options'=>['placeholder','id'=>'cadd']],
                                [
                                    'label'=>'&nbsp;',
                                    'columns'=>6,
                                    'attributes'=>[
                                        'rt'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'crt']],
                                        'rw'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'crw']],
                                        'keldes'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckd']],
                                        'kec'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckc']],
                                    ],
                                ],
                                [
                                    'label'=>'&nbsp;',
                                    'columns'=>4,
                                    'attributes'=>[
                                        'kota'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'ckt']],
                                        'propinsi'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Propinsi'],],],'options'=>['id'=>'cpr'],'columnOptions'=>['colspan'=>2]],
                                        'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'ckdp']],
                                    ],

                                ],
                                'kewarganegaraan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Warga negara'],'options'=>['id'=>'cng']],

                                #alamat tinggal

                                'add1'=>[
                                    'label'=>'Alamat Tinggal',
                                    'columns'=>6,
                                    'attributes'=>[
                                        'add1'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini','id'=>'cadd1'],'columnOptions'=>['colspan'=>5]],
                                        'sama'=>['label'=>'Sesuai KTP','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1,'id'=>'csm'] ]
                                    ]
                                ],
                                [
                                    'label'=>'&nbsp;',
                                    'columns'=>6,
                                    'attributes'=>[
                                        'rt1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'crt1']],
                                        'rw1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'crw1']],
                                        'keldes1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckd1']],
                                        'kec1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'ckc1']],
                                    ],
                                ],
                                [
                                    'label'=>'&nbsp;',
                                    'columns'=>2,
                                    'attributes'=>[
                                        'kota_tinggal'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'ckt1']],
                                        'kode_pos_tinggal'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'ckdp1']],
                                    ],

                                ],
                                [
                                    'label'=>'Kontak',
                                    'columns'=>2,
                                    'attributes'=>[
                                        'tlp'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tlp.'],],],],
                                        'email'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Email'],],],'type'=> Form::INPUT_TEXT,],
                                    ],

                                ],
                                'ibu_kandung'=>['label'=>'Ibu Kandung','type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Ibu Kandung']],
                                'status_data'=>[
                                    'label'=>'Kirim konfirmasi perubahan data ke admin',
                                    'type'=> Form::INPUT_CHECKBOX,
                                    'options'=>['placeholder'=>'Nama Ibu Kandung']
                                ],
                                [
                                    'type'=> Form::INPUT_RAW,
                                    'value'=>"<div class='pull-right'>".Html::submitButton('<i class="fa fa-save"></i> Simpan Perubahan Data',['class'=>'btn btn-primary'])."</div>"
                                ],
                            ],
                        ])
                        ."<span id='chadd1' style='display: none'></span>
                        <span id='chrt1' style='display: none'></span>
                        <span id='chrw1' style='display: none'></span>
                        <span id='chkd1' style='display: none'></span>
                        <span id='chkc1' style='display: none'></span>
                        <span id='chkt1' style='display: none'></span>
                        <span id='chkdp1' style='display: none'></span>
                    
                    ";

                        ?>
                        <?php ActiveForm::end();
                        echo"</div>";
                    }
                    ?>
            </div>
        </div>
    </div>
<?php

$this->registerJs(
    '
    $(document).ready(
        function () {

            $("#cadd1").change(function(){$("#chadd1").val($("#cadd1").val());});$("#crt1").change(function(){$("#chrt1").val($("#crt1").val());});$("#crw1").change(function(){$("#chrw1").val($("#crw1").val());});$("#ckd1").change(function(){$("#chkd1").val($("#ckd1").val());});$("#ckc1").change(function(){$("#chkc1").val($("#ckc1").val());});$("#ckt1").change(function(){$("#chkt1").val($("#ckt1").val());});$("#ckdp1").change(function(){$("#chkdp1").val($("#ckdp1").val());});

            $("#csm").click(function () {
                $("#cadd1").val($("#chadd1").val());$("#crt1").val($("#chrt1").val());$("#crw1").val($("#chrw1").val());$("#ckd1").val($("#chkd1").val());$("#ckc1").val($("#chkc1").val());$("#ckt1").val($("#chkt1").val());$("#ckdp1").val($("#chkdp1").val());
                if($("#csm:checked").length===1){
                    $("#cadd1").val($("#cadd").val());$("#crt1").val($("#crt").val());$("#crw1").val($("#crw").val());$("#ckd1").val($("#ckd").val());$("#ckc1").val($("#ckc").val());    
                    $("#ckt1").val($("#ckt").val());$("#ckdp1").val($("#ckdp").val());    
                }              
            });
        }
    );
    
    
    
'
);

?>
