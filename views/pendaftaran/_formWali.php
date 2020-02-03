<?php
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
?>
<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">Biodata Wali</span>
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
        'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No KTP']],
        'nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap']],
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
                    'local'=>app\models\Functdb::agama_dft(1),
                    'limit'=>10,
                ]],
                'options' => ['fullSpan'=>6,'placeholder' => 'Agama',],
            ],
        ],
        'jk'=>['label'=>'Jenis Kelamin','type'=> Form::INPUT_RADIO_LIST,'items'=>[0=>'Perempuan','Laki-Laki'],'options'=>['inline'=>true]],
        'status_ktp'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
                    'local'=>app\models\Functdb::statKTP(1),
                    'limit'=>10,
                ]],
                'options' => ['fullSpan'=>6,'placeholder' => 'Status Pernikahan',],
            ],
        ],
        'pekerjaan'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
                    'local'=>app\models\Functdb::kerja_dft(1),
                    'limit'=>10,
                ]],
                'options' => ['fullSpan'=>6,'placeholder' => 'Pekerjaan',],
            ],
        ],
        [
            'label'=>'Alamat KTP',
            'columns'=>6,
            'attributes'=>[
                'add'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini'],'columnOptions'=>['colspan'=>4],'options'=>['id'=>'wadd']],
                'sama'=>['label'=>'Sesuai Dengan Mahasiswa','type'=> Form::INPUT_CHECKBOX,'options'=>['name'=>'sm1','value'=>1,'id'=>'wsmm'],'columnOptions'=>['colspan'=>2]]
            ]
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>6,
            'attributes'=>[
                'rt'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'wrt']],
                'rw'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'wrw']],
                'keldes'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'wkd']],
                'kec'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'wkc']],
            ],
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>4,
            'attributes'=>[
                'kota'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'wkt']],
                'propinsi'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Propinsi'],],],'options'=>['id'=>'wpr'],'columnOptions'=>['colspan'=>2]],
                'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'wkdp']],
            ],

        ],
        'kewarganegaraan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Warga negara'],'options'=>['id'=>'wng']],
        #alamat tinggal

        'add1'=>[
            'label'=>'Alamat Tinggal',
            'columns'=>6,
            'attributes'=>[
                'add1'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini','id'=>'wadd1'],'columnOptions'=>['colspan'=>5]],
                'sama'=>['label'=>'Sesuai KTP','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1,'id'=>'wsm'] ]
            ]
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>6,
            'attributes'=>[
                'rt1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],'options'=>['id'=>'wrt1']],
                'rw1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],'options'=>['id'=>'wrw1']],
                'keldes1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'wkd1']],
                'kec1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],'options'=>['id'=>'wkc1']],
            ],
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>2,
            'attributes'=>[
                'kota_tinggal'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],'options'=>['id'=>'wkt1']],
                'kode_pos_tinggal'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,'options'=>['id'=>'wkdp1']],
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


?>
<span id='whadd' style='display: none'></span>
<span id='whrt' style='display: none'></span>
<span id='whrw' style='display: none'></span>
<span id='whkd' style='display: none'></span>
<span id='whkc' style='display: none'></span>
<span id='whkt' style='display: none'></span>
<span id='whkdp' style='display: none'></span>
<span id='whpr' style='display: none'></span>
<span id='whng' style='display: none'></span>

<span id='whadd1' style='display: none'></span>
<span id='whrt1' style='display: none'></span>
<span id='whrw1' style='display: none'></span>
<span id='whkd1' style='display: none'></span>
<span id='whkc1' style='display: none'></span>
<span id='whkt1' style='display: none'></span>
<span id='whkdp1' style='display: none'></span>
