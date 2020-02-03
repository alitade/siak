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
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pengajar', 'url' => ['ajr']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['ajr-view', 'id' => $model->ds->ds_nm]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-update">
   	<div class="page-header"><h3><?= Html::encode($this->title) ?></h3></div>
    
    <div class="col-sm-6">
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
	<?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
	<div>
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
	?>
	</div>
    <div class="panel-body">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
	<?php ActiveForm::end(); ?>

	</div>
    <div class="col-sm-6">
    <?php if($modJdw_):?>
    <div>	
    	<h4>Info Perubahan</h4>
		<?php if($modBn){ echo $nBn; }

	
	$n=0; 
	$Lbn="";$Ljd="";$Ljr="";$Lh="";
	$data="
	<div>
	<table class='table'>
	<tr>
		<th> Jadwal </th>
		<th> Jurusan </th>
		<th> Matakuliah </th>
		<th> APP | &sum; </th>
		<th>  </th>
	</tr>";

	$n=0; 
	$Lbn	="";$Ljd="";$Ljr="";$Lh="";
	$data	="<div><table>";
	$n1=0;
	$hide=0;
	foreach($modJdw_ as $d){
		$n++;
		
		if($d['totMhs']>0){$hide++;}
		if($Lbn!=$d['GKode']){
			$n1=0;
			$Lbn=$d['GKode'];
			$data.="
			</table>
			</div>
			<div class='col-sm-12'>
			<h3>".$Lbn." "
			.Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Sub Jadwal',["akademik/ajr-view",'id'=>$d['bn_id'],'pid'=>$d['jdwl_id']],['class'=>'btn btn-success'])."</h3>
			<table class='table table-bordered'>
				<tr>
					<th> Jadwal </th>
					<th> Jurusan </th>
					<th> Matakuliah (KLS | SKS)</th>
					<th> APP | &sum; </th>
					<th></th>
				</tr>
			";
		}

		$jdwl=explode("|",$d['jadwal']);
		$jd = "";
		foreach($jdwl as $k=>$v){
			$Info=explode('#',$v);
			$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
			$jd.=($d['s']>0 && $Info[0]==$d['id']?'<i class="glyphicon glyphicon-lock"></i> ':
					Html::a('<i class="glyphicon glyphicon-trash"></i>',['akademik/del-ch','id'=>$Info[0]]
					,['onClick'=>'return confirm("Hapus Jadwal Hari '."$ket".'")'])
				)." ".Html::a('<i class="glyphicon glyphicon-pencil"></i>',['#']).($Info[0]==$d['id']?" <b>$ket</b>":" $ket")
				
				."<br />";
		}
		$jdwl=$jd;

		if($Ljr!=$Lbn.'|'.$d['jr_jenjang']."|".$d['jr_nama']."|".$d['pr_kode']){			
			$Ljr=$Lbn.'|'.$d['jr_jenjang']."|".$d['jr_nama']."|".$d['pr_kode'];
			$data.='<tr>'
				.($n1>0?"":'<td rowspan="24">'.$jdwl.'</td>')
				.'<td>'
				.$d['jr_jenjang']." ".$d['jr_nama']
				." (".$d['pr_nama'].")<br />".'</td>
				<td>'.$d['mtk_kode']." ".$d['mtk_nama']." ($d[jdwl_kls] | $d[mtk_sks])".' </td>
				<td>'.$d['app'].' | '.$d['totMhs'].' </td>
				<td>'
				.($d['totMhs']>0? "":
				Html::a('<i class="glyphicon glyphicon-trash"></i>',['akademik/del-gp','id'=>$d['id']]
					,[
						'onClick'=>
						'return confirm("Hapus Jadwal '." $d[jr_jenjang] $d[jr_nama]|$d[mtk_kode]-$d[mtk_nama] ($d[jdwl_kls] | $d[mtk_sks])".'")'
					]
				))
				.'</td>
			</tr>';
			
		}
		$n1++;

	}
	
	echo $data."</table></div>";
	?>
    </div>
    <br />
    <?php
	endif;
	?>
    
    </div>
    
</div>
