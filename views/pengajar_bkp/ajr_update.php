<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Funct;

use kartik\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Update Pengajar: ' . ' ' . $model->ds->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pengajar', 'url' => ['/pengajar/index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['/pengajar/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-primary">
   	<div class="panel-heading"><h4 class="panel-title"><?= Html::encode($this->title) ?></h4></div>
    <div class="panel-body">
	<?php 
	$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
		'kln_id'=>[
			'label'=>'Kurikulum',
			'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->kr_kode." - ".$model->kln->kr->kr_nama,
		], 

		'jurusan'=>[
			'label'=>'Jurusan',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->jr->jr_id.": ".$model->kln->jr->jr_jenjang." ".$model->kln->jr->jr_nama,
		], 

		'program'=>[
			'label'=>'Program',
            'type'=>Form::INPUT_STATIC,
			'staticValue'=>$model->kln->pr->pr_nama,
		], 

		'mtk_kode'=>[
            'type'=>Form::INPUT_WIDGET,
			'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => app\models\Funct::MTK(1,['jr_id'=>$model->kln->jr_id]),
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => '... ',
                ],
				'pluginOptions' => [
					'allowClear' => true
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
    ]


    ]);
	?>
	
    
	<div class="col-sm-6">
    <?php if($modJdw): ?>
    	 Info Jadwal<br />
		<table class="table table-bordered">
        <thead>
        	<tr>
            	<th>No</th>
            	<th>Jadwal</th>
            	<th>Kls.</th>
            </tr>
        </thead>
        <tbody>
		<?php
		$n=0;
        foreach($modJdw as $d):$n++;
			$jdwl	= explode("|",$d['jadwal']);
			$jd="";
			foreach($jdwl as $k=>$v){
				$Info=explode('#',$v);
				$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]<br />";
				$jd.=$ket;
			}
			$jdwl=$jd;
        ?>
        	<tr>
            	<td><?= $n ?></td>
            	<td><?= $jdwl ?></td>
            	<td><?= $d->jdwl_kls ?></td>
            </tr>
        <?php
		//echo Funct::HARI()[$d->jdwl_hari]." ".$d['jadwal']." $d->jdwl_masuk - $d->jdwl_keluar <br />";
        endforeach;
        ?>
        </tbody>
		</table>
	<?php	
	endif;
	$gab=0;
	?>
	</div>
    <div class="col-sm-6">
    <?php if($modJdw_):$gab=1;?>
    Info Jadwal 1<br />
    <table class="table">
    	<thead>
        <tr>
        	<th>No.</th>
        	<th>Matakuliah</th>
        	<th>Jadwal</th>
        </tr>
        </thead>
        <tbody>
        <?php $n=0; foreach($modJdw_ as $d): $n++;?>
        <tr>
        	<td><?= $n ?></td>
        	<td><?= "$d[mtk]: $d[matkul]($d[kls])" ?></td>
        	<td><?= Funct::HARI()[$d['h']].", $d[m] - $d[k]" ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
	    
    </table>
    	
    <?php endif; ?>
    
    </div>
    
    <div class="panel-body col-sm-12">
        <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Ubah'),['class' =>'btn btn-primary'])
		." ".($gab==1?Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Gabungkan'),['class' =>'btn btn-primary','name'=>'gab']):" ")
		; ?>
    </div>
	<?php ActiveForm::end(); ?>

	</div>
    
    
    
</div>
