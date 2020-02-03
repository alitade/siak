<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
#\app\models\Funct::v(app\models\Functdb::informasi(1));
?>

<div class="panel panel-default">
    <div class="panel-heading"><span class="panel-title"> Input Data Calon Mahasiswa </span></div>
    <div class="panel-body">
        <div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:16px;font-weight:bold">Biodata</span>
            <div class="pull-right">
            </div>
            <div style="clear: both"></div>
        </div>
        <p></p>
        <?=
        Form::widget([
            'model' => $mBio,
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
                'jk'=>['label'=>'Jenis Kelamin','type'=> Form::INPUT_RADIO_LIST,
                    'items'=>[0=>'Perempuan','Laki-Laki'],
                    'options'=>['inline'=>true]
                ],
                'add'=>['label'=>'Alamat KTP','type'=> Form::INPUT_TEXT,'options'=>['placeholder']],
                [
                    'label'=>'&nbsp;',
                    'columns'=>6,
                    'attributes'=>[
                        'rt'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],],
                        'rw'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],],
                        'keldes'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],],
                        'kec'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],],
                    ],
                ],
                [
                    'label'=>'&nbsp;',
                    'columns'=>2,
                    'attributes'=>[
                        'kota'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],],
                        'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,],
                    ],

                ],
                [
                    'label'=>'&nbsp;',
                    'columns'=>2,
                    'attributes'=>[
                        'propinsi'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Propinsi'],],],],
                        'negara'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Negara'],],],'type'=> Form::INPUT_TEXT,],
                    ],

                ],
                #alamat tinggal

                'add1'=>[
                    'label'=>'Alamat Tingal',
                    'columns'=>6,
                    'attributes'=>[
                        'add1'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini'],'columnOptions'=>['colspan'=>5]],
                        'sama'=>['label'=>'Sesuai KTP','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1]]
                    ]
                ],
                [
                    'label'=>'&nbsp;',
                    'columns'=>6,
                    'attributes'=>[
                        'rt1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],],
                        'rw1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],],
                        'keldes1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],],
                        'kec1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],
                        ],
                    ],
                ],
                [
                    'label'=>'&nbsp;',
                    'columns'=>2,
                    'attributes'=>[
                        'kota_tinggal'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],],
                        'kode_pos_tinggal'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,],
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
            ],
        ]);
        ?>


        <div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:16px;font-weight:bold">Informasi Pendidikan Akhir</span>
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
                'jenjang_akhir'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
                            'local'=>app\models\Functdb::jenjang_dft(1),
                            'limit'=>10,
                        ]],
                        'options' => ['fullSpan'=>6,'placeholder' => 'SMA / D1 / S1',
                        ],
                    ],
                ],
                [
                    'columns'=>2,
                    'label'=>'Asal Sekolah',
                    'attributes'=>[
                        'asal_sekolah'=>[
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                            'options'=>[
                                'dataset' => [[
                                    'local'=>app\models\Functdb::sch_dft(1),
                                    'limit'=>10,
                                ]],
                                'options' => ['fullSpan'=>6,'placeholder' => 'Sekolah / Universitas / PT asal',
                                ],
                            ],

                        ],
                        'status_sekolah'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['swasta'=>'Swasta','negeri'=>'Negeri',],'options'=>['inline'=>true,],],
                    ]
                ],
                'alamat_sekolah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Alamat Sekolah...']],
                'jurusan_di_sekolah'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
                            'local'=>app\models\Functdb::jr_dft(1),
                            'limit'=>10,
                        ]],
                        'options' => ['fullSpan'=>6,'placeholder' => 'S1 Teknik Informatika /IPA / IPS',
                        ],
                    ],
                ],
                'semester_akhir'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => ['1'=>'1',2,3,4,5,6,7,8],
                        'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'hint'=>'Isi dengan semester akhir yang selesai ditempuh oleh calon mahasiswa di universitas / PT yang lama jika calon mahasiswa merupakan mahasiswa yang melanjutkan kuliah'
                ],
                'nomor_sttb'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No STTB / No Ijazah']],
                'tahun_lulus'=>[
                    'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>[
                        'type'=>DateControl::FORMAT_DATE],
                ],
            ]

        ]);

        ?>

        <div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:16px;font-weight:bold">Jurusan Pilihan</span>
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
                #'fakultas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fakultas...']],
                'program_studi'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => app\models\Functdb::jurusan_plh(),
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],

                ],
                #'status_terima'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status Terima...']],
                'ket_program'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                    'options'=>[
                        'type'=>2,
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'select2Options'=>	[
                            'pluginOptions'=>['allowClear'=>true]
                        ],
                        'pluginOptions' => [
                            'depends'		=>	['pendaftaran-program_studi'],
                            'url' 			=> 	Url::to(['/json/jrpr']),
                            'loadingText' 	=> 	'Loading...',
                        ],
                    ],
                    #'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ket Program...']
                ],
                'ket_beasiswa'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => app\models\Functdb::beasiswa(),
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => '... ',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],

                ],
                'informasi_usb_ypkp'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
                            'local'=>app\models\Functdb::informasi(1),
                            'limit'=>10,
                        ]],
                        'options' => ['fullSpan'=>6,'placeholder' => 'Sumber Informasi',
                        ],
                    ],

                ],
            ]

        ]);
        ?>
    </div>
</div>



<?php
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>


<!--
<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold">Biodata Wali Siswa</span>
    <div class="pull-right">
    </div>
    <div style="clear: both"></div>
</div>
<p></p>
<?=
Form::widget([
    'model' => $mWali,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'no_ktp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'No KTP']],
        'nama'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Nama Lengkap']],
        [
            'label'=>'Tempat & Tanggal Lahir',
            'columns'=>2,
            'attributes'=>[
                'tempat_lahir'=>[
                    'type'=> Form::INPUT_TEXT,
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tempat'],],],
                ],
                'tanggal_lahir'=>[
                    'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]
                    #'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Tgl'],],],
                ],
            ]

        ],
        'jk'=>['label'=>'Jenis Kelamin','type'=> Form::INPUT_RADIO_LIST,
            'items'=>[0=>'Perempuan','Laki-Laki'],
            'options'=>['inline'=>true]
        ],
        'add'=>['label'=>'Alamat KTP','type'=> Form::INPUT_TEXT,'options'=>['placeholder']],
        [
            'label'=>'&nbsp;',
            'columns'=>6,
            'attributes'=>[
                'rt'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],],
                'rw'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],],
                'keldes'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],],
                'kec'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],],
            ],
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>2,
            'attributes'=>[
                'kota'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],],
                'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,],
            ],

        ],
        [
            'label'=>'&nbsp;',
            'columns'=>2,
            'attributes'=>[
                'propinsi'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Propinsi'],],],],
                'kode_pos'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Negara'],],],'type'=> Form::INPUT_TEXT,],
            ],

        ],
        #alamat tinggal

        'add1'=>[
            'label'=>'Alamat Tingal',
            'columns'=>6,
            'attributes'=>[
                'add1'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Alamat Tinggal Saat Ini'],'columnOptions'=>['colspan'=>5]],
                'sama'=>['label'=>'Sesuai KTP','type'=> Form::INPUT_CHECKBOX,'options'=>['value'=>1]]
            ]
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>6,
            'attributes'=>[
                'rt1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RT'],],],],
                'rw1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'RW'],],],],
                'keldes1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kel./Des.'],],],'columnOptions'=>['colspan'=>2],],
                'kec1'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kec.'],],],'columnOptions'=>['colspan'=>2],
                ],
            ],
        ],
        [
            'label'=>'&nbsp;',
            'columns'=>2,
            'attributes'=>[
                'kota_tinggal'=>['type'=> Form::INPUT_TEXT,'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kab./Kota'],],],],
                'kode_pos_tinggal'=>['fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kode Pos'],],],'type'=> Form::INPUT_TEXT,],
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

-->