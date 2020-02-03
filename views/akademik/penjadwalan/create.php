<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\widgets\DepDrop;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;



/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Tambah Jadwal';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Kuliah', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-create">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>


<div class="bobot-nilai-form col-sm-6">
<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]); ?>
<div class="panel panel-primary">
    <div class="panel-heading">Dosen Pengajar</div>
    <div class="panel-body">
    <?php 
	
	echo Form::widget([
    'model' => $modelBn,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kalender'=>[
			'label'=>'Akademik',
			'options'=>['placeholder'=>'Tahun Akademik'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => 
					ArrayHelper::map(app\models\Kalender::find()->all(),'kr_kode',
						function($model,$defaultValue){
							//print_r($model->kr->kr_nama);die();
							return $model->kr->kr_kode." : ".$model->kr->kr_nama;
						}		
					),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Tahun Akademik',
                ],
				'pluginOptions' => [
					'allowClear' => true
				],
            ],
		], 
		'jurusan'=>[
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
						'depends'		=>	['bobotnilai-kalender'],
						'url' 			=> 	Url::to(['/akademik/klnjur']),
						'loadingText' 	=> 	'Loading...',
				],
			],
		], 
		'kln_id'=>[
			'label'=>'Program',
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
						'depends'		=>	['bobotnilai-jurusan'],
						'url' 			=> 	Url::to(['/akademik/klnpro']),
						'loadingText' 	=> 	'Loading...',
				],
			],
		], 


/*
		'jp'=>[
			'label'=>'Jurusan & Program ',
			'columns'=>2,
			'labeSpan'=>4,
			'attributes'=>[

			],
		],
*/
		'mtk_kode'=>[
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
						'depends'		=>	['bobotnilai-jurusan'],
						'url' 			=> 	Url::to(['/akademik/klass']),
						'loadingText' 	=> 	'Loading...',
				],
			],
		], 
		'ds_nidn'=>[
			'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
			'options'=>[
				'data' => app\models\Funct::DSN(),
				'options' => [
					'fullSpan'=>6,
					'placeholder' => '... ',
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			],
		], 

/*
		'md'=>[
			'label'=>'Matkul & Dosen',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
			],
		],
*/		
    ]


    ]);
	?>
	
    </div>
    </div>

	<?= 
        $this->render('schedule__form', [
        'model' => $model,
        'mod' => $modelBn,
        'form' => $form,
        //'pid' => $pid,
		//'qBentrok'=>$qBentrok,
    ]) 
	?>	
    <div class="panel panel-primary">
        <div class="panel-body">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>


    
    
    <?= ""/*$this->render('jdw__form', [
        'model' => $model,
    ]) 
	*/
	?>

</div>
