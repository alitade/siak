<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;


$this->title = 'Master Perkuliahan ';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<div class="page-header"><h3><?= Html::encode($this->title) ?></h3></div>	

<div class="angge-search">

    <?php $form = ActiveForm::begin([
        'action' => ['perkuliahan'],
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
	if( isset($_GET['MasterSearch']['kr_kode']) && !empty($_GET['MasterSearch']['kr_kode'])){
	//Pjax::begin(); 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>false
            ],
            '{toggleData}',
			//'{export}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'jdwl_id','label'=>'ID',
				'width'=>'1%'
			],
			[
				'attribute'	=> 'pr_kode','label'=>'Program',
				'width'=>'10%',
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
				'value'=>function($model){return $model['pr_nama'];}
			],			
			[
				'attribute'=>'sesi','label'=>'Sesi',
				'format'=>'raw',
				'width'=>'1%'
			],
			[
				'attribute'=>'jdwl_hari',
                'label'=>'Jadwal',
				'format'=>'raw',
				'value'=>function($model){
					return \app\models\Funct::HARI()[$model['jdwl_hari']]."<br />"
					.$model['jdwl_masuk'].'-'.$model['jdwl_keluar'];
					return $model->jAwal;
				},
				'filter'=>@app\models\Funct::HARI(),
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'mtk_nama','label'=>'Matakuliah',
				'format'=>'raw',
				'value'=>function($model){
					return "<b>".$model['ds_nm']."<br />".Html::decode($model['mtk_nama'])."</b>";
				}
			],
			'pelaksana',
			[
				'label'=>'Pelaksanaan',
				'format'=>'raw',
				'value'=>function($model){
					return \app\models\Funct::TANGGAL($model['tgl_'])."<br />".$model['pMasuk'].'-'.$model['pKeluar'];
				},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'label'=>'Kehadiran',
				'format'=>'raw',
				'value'=>function($model){
					$ket="";
					if($model['dMasuk']){
						if(!$model['dKeluar']){$ket = "Finger Keluar!";}					
						if($model['xx']==='2'){$ket = $model['dKeluar']." < ".$model['jdwl_keluar'];}
					}
					
					return $model['dMasuk'].'-'.$model['dKeluar'].($ket?'<br /><span class="label label-danger"> '.$ket.'</span>':"");
				},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'format'=>'raw',
				'header'=>'&sum;Mhs<br />&sum;Hadir',
				'mergeHeader'=>true,
				'width'=>'1%',
				'value'=>function($model){
					return Html::a($model['peserta'],
					Yii::$app->urlManager->createUrl(
						['dirit/jdw-detail','id' => $model['jdwl_id']]),['title' => Yii::t('yii', 'Detail'),]
					)."<br />".$model['hadir'];
				},
				'filter'=>false
			],
			[
				'header'=>' ',
				'format'=>'raw',
				'mergeHeader'=>true,
				'value'=>function($model){
						$btn ='<i class="glyphicon glyphicon-remove-circle" style="color:red" id="_'.$model['jdwl_id'].'"></i>';
					if($model['xx']){
						$btn ='<i class="glyphicon glyphicon-ok-circle" style="color:green"></i>';
					}
					return $btn;
				}
			],
			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>'<li>{edit}</li><li>{Fganti}</li><li>{ganti}</li>',
				'buttons' => [
					'edit'=> function ($url, $model, $key) {
						return Html::a('<i class="glyphicon glyphicon-pencil"></i> Update Kehadiran'
						,['dirit/detail-perkuliahan','id' => $model['jdwl_id'],'s'=>$model['sesi']]);
					},						
					'Fganti'=> function ($url, $model, $key) {
						//if($model->status==1){return false;}
						$kode= "";//str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
						return Html::a('<i class="glyphicon glyphicon-file"></i>Form Pergantian Jadwal'
						,['trx-finger/form-pergantian','id' =>$model['jdwl_id'],'k'=>$kode,'view'=>'t']);
					},						
					'ganti'=> function ($url, $model, $key){
						//if($model->status==1){return false;}
						$kode= "";//str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
						return Html::a('<i class="glyphicon glyphicon-file"></i>Input Pergantian Jadwal'
						,['rekap-absen/create-pergantian','id' =>$model['jdwl_id'],'k'=>$kode,'view'=>'t']);
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
        	'heading'=>'<i class="fa fa-navicon"></i> Master Perkuliahan',
			'after'=>false,
    	]
    ]); //Pjax::end(); 
	}
	?>

</div>
