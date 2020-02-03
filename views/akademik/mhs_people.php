<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\builder\Form;
use app\models\Funct;
use kartik\datecontrol\DateControl;
use kartik\widgets\DepDrop;


/**
 * @var yii\web\View $this
 * @var app\models\People $model
 */

$this->title = $modPeople->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = ['label' => 'Detail', 'url' => ['mhs-view','id'=>$model->mhs_nim]];
$this->params['breadcrumbs'][] = $this->title;
?>


    <?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]); 
	echo Form::widget([
    'model' => $modPeople,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'Nama'=>[
			'label'=>'Nama Lengkap',
            'type'=>Form::INPUT_TEXT, 
        ],  
        'tempat_lahir'=>[
            'type'=>Form::INPUT_TEXT, 
        ],  
		'tanggal_lahir'=>[
			'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
			'options'=>[
				'attribute' => 'jdwl_uas',
				'pluginOptions' => ['format' => 'yyyy-mm-dd',]					
			]
		],
        'propinsi'=>['label'=>'Provinsi','type'=>Form::INPUT_TEXT,],
        'kota'=>['label'=>'Kota','type'=>Form::INPUT_TEXT,],
        /*
        'propinsi'=>[
			'label'=>'Provinsi',
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>Funct::PROVINSI(),
			],
        ],  
		'kota'=>[
			'label'=>'Kota',
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
						'depends'		=>	['people-propinsi'],
						'url' 			=> 	Url::to(['/mahasiswa/prov']),
						'loadingText' 	=> 	'Loading...',
				],
            ],
		],
        */
		'jln'=>['label'=>'Jalan','type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Jalan',]], 
		[
			'label'=>'RT/RW/Dusun',
			'columns'=>3,
			'labeSpan'=>2,
			'attributes'=>[
				'rt'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'RT',]], 
				'rw'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'RW',]], 
				'dsn'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Dusun']], 
			],
		],
		[
			'label'=>'Kel/Kec',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
				'kel'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Kelurahan',]], 
				'kec'=>['type'=> Form::INPUT_TEXT,'options'=>['placeholder'=>'Kecamatan',]], 
			],
		],

		[
			'label'=>' ',
			'type'=>Form::INPUT_RAW,
			'value'=>
				Html::submitButton($modPeople->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah')
				, ['class' => $modPeople->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
		]
			

    ]
    ]);


?>
<?php ActiveForm::end(); ?>
