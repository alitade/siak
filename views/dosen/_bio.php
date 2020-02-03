<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use yii\web\View;
use app\models\Funct;


?>
<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">Biodata</span>
    <div class="pull-right">
    </div>
    <div style="clear: both"></div>
</div>
<p></p>

<?=
Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        [
            'label'=>'No. KTP',
            'columns'=>2,
            'attributes'=>[
                'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No KTP']],
                [
                    'label'=>'',
                    'type'=>Form::INPUT_RAW,
                    'value'=>''
                        .(Funct::acc('/json/cek-ktp')?
                            Html::img(Url::to("@web/images/load.gif"),['width'=>'20px','id'=>'loading-image'])
                            .Html::a('Cek NO.Ktp','javascript:;',['class'=>'btn btn-primary','id'=>'cKtp'])
                            :''
                        )
                    ,
                ]
            ],
        ],

        [
            'label'=>'Nama',
            'columns'=>4,
            'attributes'=>[
                'glr_depan'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Ex: Dr.'],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Gelar'],],],
                ],
                'nama'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap'],
                    'columnOptions'=>['colspan'=>2],

                ],
                'glr_belakang'=>[
                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Ex: S.T.,'],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Gelar'],],],
                ],


            ]

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
    ],
]);


echo"
    <span id='chadd1' style='display: none'></span>
    <span id='chrt1' style='display: none'></span>
    <span id='chrw1' style='display: none'></span>
    <span id='chkd1' style='display: none'></span>
    <span id='chkc1' style='display: none'></span>
    <span id='chkt1' style='display: none'></span>
    <span id='chkdp1' style='display: none'></span>

";


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
    );'
);


if(Funct::acc('/json/cek-ktp')){
    $this->registerJs("
    $('#loading-image').hide();
    
    $('#cKtp').click(function () {
        var href = $(this);
        $.ajax({
            url : '".Url::to(['/json/cek-ktp'])."',
            type: 'POST',
            beforeSend: function() { $('#loading-image').show(); },
            data:{
                ktp:$('#biodata-no_ktp').val(),
            },
            success: function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                if (data.message !='') {alert(data.message);}
                $('#loading-image').hide();
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error : ' + textStatus);
            }
        });
       });
    ", View::POS_END, 'noktpFunction');
}
?>
