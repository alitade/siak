<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

#\app\models\Funct::v($DATA);
$DefKon = $DATA['DEF'];

#echo $DATA['tot']."asd";
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
        [
            'columns'=>4,
            'label'=>'Asal Sekolah',
            'attributes'=>[
                'kd_jenjang'=>[
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => app\models\Functdb::jenjang_dft(0),
                        'options' => ['fullSpan'=>6,'placeholder' => 'Jenjang',],
                        'pluginOptions' => ['allowClear' => true],
                    ],

                ],
                'asal_sekolah'=>[
                    'columnOptions'=>['colspan'=>2],
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                    'options'=>[
                        'dataset' => [[
                            'local'=>app\models\Functdb::sch_dft(1)?:[''],
                            'limit'=>10,
                        ]],
                        'options' => ['fullSpan'=>6,'placeholder' => 'Sekolah / Universitas / PT asal',],
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
                    'local'=>app\models\Functdb::jr_dft(1)?:[''],
                    'limit'=>10,
                ]],
                'options' => ['fullSpan'=>6,'placeholder' => 'S1 Teknik Informatika /IPA / IPS',],
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


<?php
if($DATA['tot']>1){
    $listKons=[];
    foreach ($DATA['model']->all() as $d){$listKons[$d->id_konsultan]=$d->kon->vendor;}
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'id_konsultan'=>[
                'label'=>'Konsultan',
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => $listKons,
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],

            ],
            'program_studi'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                'options'=>[
                    'type'=>2,
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions' => [
                        'depends'		=>	['pendaftaran-id_konsultan'],
                        'url' 			=> 	Url::to(['/json/konjrpr','t'=>'jr']),
                        'loadingText' 	=> 	'Loading...',
                        #'params'=>['idk']
                    ],
                ],
            ],
            'pr_kode'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                'options'=>[
                    'type'=>2,
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions' => [
                        'depends'		=>	['pendaftaran-program_studi'],
                        'url' 			=> 	Url::to(['/json/konjrpr','t'=>'pr']),
                        'loadingText' 	=> 	'Loading...',
                        'params'=>['pendaftaran-id_konsultan']
                    ],
                ],
            ],
            'informasi_usb_ypkp'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                'options'=>[
                    'dataset' => [[
                        'local'=>app\models\Functdb::informasi(1)?:[''],
                        'limit'=>10,
                    ]],
                    'options' => ['fullSpan'=>6,'placeholder' => 'Sumber Informasi',
                    ],
                ],

            ],
        ]

    ]);
}else{
    if($DATA['tot']==1){$DefKon = $DATA['model']->one()->id_konsultan;}
    echo $form->field($model,'id_konsultan')->hiddenInput(['value'=>$DefKon])->label(false);
    #echo Html::hiddenInput(['idk'],$DefKon,['id'=>'idk']);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            #'fakultas'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Fakultas...']],
            'program_studi'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                'options'=>[
                    'data' => app\models\Functdb::konJrPr($DefKon?:0),
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => '... ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],

            ],
            'pr_kode'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                'options'=>[
                    'type'=>2,
                    'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                    'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions' => [
                        'depends'		=>	['pendaftaran-program_studi'],
                        'url' 			=> 	Url::to(['/json/konjrpr','t'=>'pr']),
                        'loadingText' 	=> 	'Loading...',
                        'params'=>['pendaftaran-id_konsultan']
                    ],
                ],
            ],
            'informasi_usb_ypkp'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
                'options'=>[
                    'dataset' => [[
                        'local'=>app\models\Functdb::informasi(1)?:[''],
                        'limit'=>10,
                    ]],
                    'options' => ['fullSpan'=>6,'placeholder' => 'Sumber Informasi',
                    ],
                ],

            ],
        ]

    ]);

}

?>
