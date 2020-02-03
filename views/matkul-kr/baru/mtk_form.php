<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use kartik\widgets\DepDrop;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="matkul-form">
<div class="panel panel-primary">
    <div class="panel-heading">Tambah Matakuliah Non Konsentrasi</div>
    <div class="panel-body">
    <?php 
	//print_r($model->getErrors());

    $MK = \app\models\Matkul::find();
    $KODE=[];$MATKUL=[];
    foreach ($MK->all() as $d){
        if(!array_search($d->mtk_kode,$KODE)){$KODE[]=$d->mtk_kode;}
        if(!array_search($d->mtk_nama,$MATKUL)){$MATKUL[]=$d->mtk_nama;}
    }
    $KODE_=[];
    foreach ($MK->where("mtk_kode in(select kode from matkul_kr_det where id_kr=$KR->id and isnull(Rstat,0)=0) ")->all() as $d){
        $KODE_[$d->id]=$d->mtk_kode.": ".$d->mtk_nama;
    }

    //$model->level=2;
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

        #'level'=>['type'=> Form::INPUT_RADIO_LIST,'items'=>[0=>'Universitas','Fakultas','Program Studi'],'options'=>['inline'=>true]],
		'mtk_kode'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Typeahead',
            'options'=>[
                'dataset' => [[
					'local'=>$KODE?$KODE:[""],
					'limit'=>5,
				]],
                'options' => ['fullSpan'=>6,'placeholder' => 'Kode Matakuliah',
                ],
            ],
		],
		[
            'label'=>'Matakuliah',
            'columns'=>1,
            'attributes'=>[
                'mtk_nama'=>[
                    'type'=>Form::INPUT_TEXT,
                    'options' => [
                        'fullSpan'=>6,
                        'placeholder' => 'Matakuliah',
                    ],
                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'IND'],],],
                ],
                #'mtk_en'=>['type'=>Form::INPUT_TEXT,'options' => ['fullSpan'=>6,'placeholder' => 'courses',],'fieldConfig'=>['addon'=>['prepend' =>['content'=>'ENG'],],],],

            ]

        ],
        'mtk_sks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'SKS']],
        #'status'=>['type'=> Form::INPUT_RADIO_LIST,'items'=>[0=>'Wajib','Pilihan'],'options'=>['inline'=>true]],
        'mtk_kat'=>[
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['0'=>'Teori','1'=>'Praktek','2'=>'Teori + Praktek','3'=>'Tugas Besar','4'=>'Teori + Praktek + Tugas Besar','5'=>'Teori + Tugas Besar'],#\app\models\Functdb::KatMk(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Kategori Matakuliah',
                ],
                'pluginOptions' => ['allowClear' => true],
            ],
        ],
        'mtk_semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Semester']],
        'mtk_sub'=>[
            'label'=>'Syarat',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => $KODE_?$KODE_:[""],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Syarat Matakuliah',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],
        ],
        'ig'=>[
                'label'=>'Abaikan Jadwal Perkuliahan',
                'type'=> Form::INPUT_CHECKBOX,
            ],

    ]


    ]);
    ?>
	
    <div class="panel-body">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
    </div>
    
</div>
