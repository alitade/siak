<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
    <div class="page-header">
        <h3><?php 
			echo $ModBn->kln->kr_kode." ( ".$ModBn->ds->ds_nm." : ".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama." ) ";
		?></h3>
    </div>
    <div class="col-sm-16">
    	<table class="table">
        	<thead>
            	<tr>
                	<td>Program</td>
                	<td>Hari</td>
                	<td>Jadwal</td>
                	<td>Kelas</td>
                	<td>Ruang</td>
                	<td> Total Mahasiswa </td>
                </tr>
            </thead>
            <tbody>
        	<tr>
            	<td> </td>
            	<td><?= Funct::HARI()[$ModJd->jdwl_hari]?></td>
                <td><?= $ModJd->jdwl_masuk."-".$ModJd->jdwl_keluar?></td>
                <td><?= $ModJd->jdwl_kls?></td>
                <td><?= $ModJd->rg_kode?></td>
                <td><?= $ModKrs->find()->where(['jdwl_id'=>$ModJd->jdwl_id,'isnull(tbl_krs.RStat,0)'=>0])->count()?></td>
            </tr>
            </tbody>
        </table>
    </div>
	<?php
	/* 
        $this->render('../akademik/schedule__form', [
        'model' => $model2,
        'mtk' => $ModBn->mtk,
    ]) 
	*/?>	

    <?php 

	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'toolbar'=> false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
				'attribute'=>'bn.ds.ds_nm',
				'value'=>function($model){return $model->bn->ds->ds_nm;}
			],
			[
				'attribute'=>'jum',
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i> Total',
				'width'=>'5%',
			],
			[
				'attribute'=>'jumabs',
				'label'=>'Nilai',
				'format'=>'raw',
				'width'=>'5%',
			],
			[
				'label'=>' ',
				'format'=>'raw',
				'value'=>function($model){
					$Jd = (int)$_GET['id'];
					return 
					'<div class="col-sm-2">'.Html::a("Manual",['bisa/ajr-split-manual','id'=>$Jd,'id1'=>$model->jdwl_id],['class'=>'btn btn-primary']).'</div>'
					;
				}
				
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Jadwal Tujuan<br />'.$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama." : ".$ModBn->kln->pr->pr_nama,
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['jdw'], ['class' => 'btn btn-info']),
    	]
    ]);
	ActiveForm::end();
	?>
</div>
