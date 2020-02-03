<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;

/* @var $this yii\web\View */
/* @var $model app\models\Ujian */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Ujians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ujian-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'IdJadwal',
            'Kat',
            'Tgl',
            'Masuk',
            'Keluar',
			
            'RgKode',
			[
				'label'=>'Ruang dan Kapasitas',
				'value'=>$model->ruang->rg_kode." | ".$model->ruang->Qujian
			],
			[
				'label'=>'Jumlah Mahasiswa',
				'value'=>\app\models\PesertaUjian::find()->where(['IdUjian'=>$model->Id])->count()
			],
			'Jml',
			'GKode'
        ],
    ]) ?>
    


<?php
	$stat	= false;
	$Kuota	= \app\models\PesertaUjian::find();
	if($Kuota->where(['IdUjian'=>$model->Id])->count() >= $model->ruang->Qujian){
		$stat=true;
	}

	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
	Pjax::begin(); 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'toolbar'=> false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'header'=>'Program',
				'value'=>function($model){
					return $model->bn->kln->pr->pr_nama;
				},
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
				'attribute'=>'bn.ds.ds_nm',
				'value'=>function($model){return $model->bn->ds->ds_nm;}
			],
			[
				'attribute'=>'jum',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i> Total',
				'width'=>'5%',
				'pageSummary'=>true,
			],
			[
				'label'=>' ',
				'format'=>'raw',
				'value'=>function($model)use($stat,$Kuota){
					$Jd = (int)$_GET['id'];
					if($stat){return false;}
					//else{if($Kuota->where([''])->count()){}}
					return 
					'<div class="col-sm-2">'.Html::input('text','qty['.$model->jdwl_id.']',false,['class'=>'form-control']).'</div>
					<div class="col-sm-2">'.Html::submitButton("Add",['class'=>'btn btn-primary','name'=>"pindah[$model->jdwl_id]"]).'</div>'
					;
				}
				
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Tujuan<br />'
			.$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama." : ".$ModBn->kln->pr->pr_nama,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]); Pjax::end(); 
	ActiveForm::end();
	?>


</div>
