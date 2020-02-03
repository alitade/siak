<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */
//echo str_repeat('0',3);
//print_r(\app\modules\transkrip\controllers\ModController::Akses(64));
$this->title = 'Cetak Transkrip Sementara';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<div class="page-header"><h3><?= Html::encode($this->title) ?></h3></div>	

<div class="bobot-nilai-form">
<div class="panel panel-primary">
    <div class="panel-heading">Dosen Pengajar</div>
    <div class="panel-body">
    <?php 
	$form = ActiveForm::begin([
		'type'=>ActiveForm::TYPE_HORIZONTAL,
		'method'=>'get',
	
	]); 
	echo Form::widget([
    'model' => $searchModel,
    'form' => $form,
    'columns' => 4,
    'attributes' => [
		'jr_id'=>[
			'label'=>false,
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::JURUSAN(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Jurusan',
					'required'=>'required',					
                ],
				'pluginOptions' => [
					'allowClear' => true,
				],
            ],
		], 
		'mhs_angkatan'=>[
			'label'=>false,
            'type'=>Form::INPUT_TEXT,
            'options'=>[
				'placeholder' => 'Angkatan',
				'required'=>true,
			],
		],		
		'pr_kode'=>[
			'label'=>false,
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::PROGRAM(),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Program',
					'options'=>['required'=>true,]
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'ws'=>[
			'label'=>false,			
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => ['Belum Lulus','Lulus'],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Status',
					'options'=>['required'=>true,]
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
	]    
	]);
?>
    </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton(
				Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Cari'), ['class' => 'btn btn-primary']
				); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>
<br /><br />
<?php 
	
	if(isset($_GET['MahasiswaSearch'])){
	//Pjax::begin(); 
	$form = ActiveForm::begin([
    	'type'=>ActiveForm::TYPE_VERTICAL,
    	'id'=>'tr',
    	'action' => Url::to(['nilai/cetak-all']),
    	'method'=>'post',
		'options'=>['target'=>'blank_']
		
		
    ]); 
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['mhs-create'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
        ],
        'columns' => [
            [
				'class' => 'kartik\grid\SerialColumn',
				'width'=>'1%',
				
			],
			[
				'class'=>'kartik\grid\CheckboxColumn',
				'name'=>'chk[]',
				'checkboxOptions' => function($model, $key, $index, $column) {
					$nim	= $model->mhs_nim;
					$kode	= '';//$model->jdwl->bn->mtk_kode;
					$tahun	= '';//$model->jdwl->bn->kln->kr_kode;
					return ['value'=>$model->mhs_nim];	
				},
				'width'=>'36px',
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
			[
				'attribute'	=> 'jr_id',
				'value'		=> @function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama;},
				'width'=>'20%',
				'filter'=>false, 
			],
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr->pr_nama;},
				'filter'=>false, 
			],
            [
                'attribute' => 'Nama',
                'width'=>'20%',
            ],
            [
                'attribute' => 'mhs_nim',
				'format'=>'raw',
				'value'=>function($model){
					return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
				}
            ],
			[
				'attribute'=>'mhs_angkatan',
				'width'=>'1%',
				'filter'=>false,
 			],
			[
				'attribute'	=> 'ws',
                'width'=>'5%',
				'format'=>'raw',
				'value'		=> function($model){
						return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
					},
				'filter'=>false, 
				
			],
			[
				'label'=>'Matkul',
				'width'=>'1%',
				'value'=>function($model){
					$q = "select count(*) t from Transkrip.dbo.t_nilai where npm='$model->mhs_nim'";
					$q = Yii::$app->db->createCommand($q)->queryOne();
					return $q['t'];
					 
				}
 			],

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-navicon"></i> Mahasiswa',
			'after'=>
				Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Cetak Transkrip',
					['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave']
			)

        ]
    ]);  //Pjax::end(); 
	ActiveForm::end();	
	}
	
	?>
</div>