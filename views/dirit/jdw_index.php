<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>	
<div class="angge-search">

    <?php $form = ActiveForm::begin([
        'action' => ['jdw'],
        'method' => 'get',
    ]); ?>
<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<div class="panel-body">
    <?= 
		$form->field($searchModel, 'kr_kode')->widget(Select2::classname(), [
			'data' =>app\models\Funct::AKADEMIK(),
			'language' => 'en',
			'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
    <?= 
		$form->field($searchModel, 'jr_id')->widget(Select2::classname(), [
			'data' =>app\models\Funct::JURUSAN(),
			'language' => 'en',
			'options' => ['placeholder' => 'Jurusan'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]);
	 ?>
	 
    <?php // echo $form->field($model, 'Tipe') ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dirit/jdw'],['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
<br /><br />

    <?php 
	if(isset($_GET['JadwalSearch']['kr_kode']) && !empty($_GET['JadwalSearch']['kr_kode'])){
	//echo Html::a('<i class="glyphicon glyphicon-refresh"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-danger','target'=>'_blank']);
	//Pjax::begin(); 
	echo $d= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['dirit/jdw-create'],['class'=>'btn btn-success']).''.
				Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-info','target'=>'_blank'])
                //Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['dirit/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
			//'{export}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'value'		=> @function($model){return $model->jr_jenjang." ".$model->jr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'value'		=> @function($model){return @$model->pr_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'group'=>true,  // enable grouping
				'subGroupOf'=>1, // supplier column index is the parent group,
				'pageSummary'=>'Total',
			],			
			[
				'attribute'=>'jdwl_hari',
				'value'=>function($model){
					return @app\models\Funct::HARI()[@$model->jdwl_hari];
				},
				'filter'=>@app\models\Funct::HARI(),
			],
			[
				'attribute'=>'jdwl_masuk',
				'value'=>function($model){return $model->jadwal;},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'jdwl_kls',
				'width'=>'5%',
				'contentOptions'=>['class'=>'row col-xs-1',],
			],
			[
				'attribute'=>'mtk_nama',
				'value'=>function($model){return Html::decode($model->mtk_nama);}
			],
			[
				'label'=>'SKS',
				'value'=>function($model){
					return Html::decode($model->bn->mtk->mtk_sks);
				},
				'pageSummary'=>true,
				
			],
			[
				'attribute'=>'ds_nm',
				'filter'=>true,
				
			],
			
			[
				'attribute'=>'rg_kode',
				'width'=>'10%',
				'value'		=> function($model){return $model->rg->rg_nama;},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::RUANG(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'...'],
				
			],	
			[
				'attribute'=>'jumabs',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i>',
				'width'=>'5%',
				'value'=>function($model){
					return Html::a($model->jum,
					Yii::$app->urlManager->createUrl(
						['dirit/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
					);
				},
				'filter'=>false
				
			],
			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>
					'

								<li>{dtl}</li>

								<li>{pergantian}</li>
								<li>{c_gnti}</li>
								

								<li>{a_abs}</li>
								<li>{a_pdf}</li>
								<li>{p_pdf}</li>
								<li>{c_uts}</li>
								<li>{c_uas}</li>
								<li>{c_abs}</li>
								<li>{rekap}</li>

								<li>{fm_awal}</li>
								<li>{fp_awal}</li>

					'
				,
				'buttons' => [
					'hps'=> function ($url, $model, $key) {
							return ($model->jum > 0 ? 
								false :
								Html::a('<i class="glyphicon glyphicon-trash"></i> Hapus',['dirit/jdw-delete','id' => $model->jdwl_id])
							);
					},
					#=================================================
					'fp_awal'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-file"></i> Form Pulang Awal'
							,['trx-finger/form','id' => $model->jdwl_id,'view'=>'t','t'=>'1'],['target'=>'_blank']);
					},
					'fm_awal'=> function ($url, $model, $key) {
							return false;
							return Html::a('<i class="glyphicon glyphicon-file"></i> Form Masuk Awal'
							,['trx-finger/form','id' => $model->jdwl_id,'view'=>'t','t'=>'2'],['target'=>'_blank']);
					},
					#=================================================

					'dtl'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Detail'
							,['dirit/jdw-view','id' => $model->jdwl_id,'view'=>'t']);
						},
					'edt'=> function ($url, $model, $key) {
							if($model->jum >0){return false;}
						
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Edit Jadwal',
								['dirit/jdw-update','id' => $model->jdwl_id,'view'=>'t']
							);
						},
					'a_abs'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Input Absen',
								['bisa/absensi','id' => $model->jdwl_id, 'matakuliah'=>$model->mtk_kode, 'sort'=>'id']
							);
						},
					'a_pdf'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Absensi Harian PDF',
								['dirit/absen-pdf','id' => $model->jdwl_id, 'jenis'=>3],['target'=>'_blank']
							);
						},
					'p_pdf'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Mahasiswa PDF',
								['perkuliahan/cetak-peserta','id' => $model->jdwl_id, 'jenis'=>2]
							);
						},
					'c_uts'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Cetak Absen UTS',
								['dirit/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>1]
							);
						},
					'c_uas'=> function ($url, $model, $key) {
							return Html::a(
								'<span class="glyphicon glyphicon-list"></span> Cetak Absen UAS',
								//['dirit/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
								['form/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
							);
					},
					'rekap'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Rekap Absen	',
							['dirit/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
							//['form/cetak-absensi','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
					'c_gnti'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Cek Jadwal Pergantian',
							['dirit/cek-pergantian','id' => $model->jdwl_id, 'jenis'=>2]
						);
					},
					'pergantian'=> function ($url, $model, $key) {
						return Html::a(
							'<span class="glyphicon glyphicon-list"></span> Pergantian Perkuliahan',
							['dirit/pergantian','id' => $model->jdwl_id]
						);
					},
						
				],
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); //Pjax::end(); 
	}
	?>

</div>
