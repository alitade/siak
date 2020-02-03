<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;


/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-view">	
	<h4 class="header"><?= $mJdwl->bn->ds->ds_nm." ".app\models\Funct::HARI()[$mJdwl->jdwl_hari].','."$mJdwl->jdwl_masuk-$mJdwl->jdwl_keluar (sesi $Sesi)" ?></h4>
    <div class="col-sm-12">
        <div class="col-sm-6">
        <?php
		$qDosen="select ds.ds_nm,u.Fid from tbl_dosen ds inner join user_ u on(u.username=ds.ds_user and u.tipe=3 and u.fid is not null) where isnull(ds.RStat,0)=0";
		$qDosen=Yii::$app->db->createCommand($qDosen)->queryAll();
		$ListDosen=[];
		foreach($qDosen as $d){$ListDosen[$d['Fid']]=$d['ds_nm'];}

		$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
		echo Form::widget([
		'form' => $form,
		'formName' => 'tf',
		'columns' => 1,
		'attributes' => [
			'ds'=>[
				'label'=>'Pengajar',
				'type'=>Form::INPUT_WIDGET,
				'widgetClass'=>'\kartik\widgets\Select2',
				'options'=>[
					'data' => $ListDosen,
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
					],
					'pluginOptions' => [
						'allowClear' => true
					],
				],
			], 
			'st'=>['label'=>'Hadir','type'=>Form::INPUT_RADIO_LIST,'items'=>['T','Y'],], 
			[
				'label'=>'',
				'type'=>Form::INPUT_RAW,
				'value'=>Html::submitButton('Update Kehadiran Dosen', ['class' => 'btn btn-primary','name'=>'dsn_update']),
			]
		]
		]);
    	?>        
        
		<?php ActiveForm::end();?>

        </div>
        
        <div class="col-sm-6">
        <table class="table">
            <tr><th>Pengajar</th><td><?=$dosen['pengajar']?></td></tr>
            <tr><th>Waktu Kehadiran</th><td><?=
				\app\models\Funct::TANGGAL($dosen['tgl_ins']).", $dosen[ds_masuk]-$dosen[ds_keluar]"
			?></td></tr>
            <tr><th>Durasi</th><td><?=$dosen['durasi_dosen']?></td></tr>
            <tr><th>Status</th><td><?=$dosen['status']?></td></tr>
        </table>
        </div>
    
    </div>
    <?php if(strtoupper($dosen['status'])=='HADIR'): 
	  $form = ActiveForm::begin();
	?>
    <div class="col-sm-12">
    	<center><span style="font-size:18px;text-align:center"><b>Absensi Mahasiswa</b></span></center>
        <?= (
			$u==0?
			Html::a('Tampilkan Form Kehadiran',Url::to()."&u=1",['class'=>'btn btn-success']):
			Html::a('Close Update',['detail-perkuliahan','id'=>$id,'s'=>$Sesi],['class'=>'btn btn-primary'])
		)
		
		?>
    	<hr />
    	<table class="table">
        	<thead>
        	<tr>
            	<th>No</th>
            	<th>NPM</th>
            	<th>NAMA</th>
            	<th>MASUK</th>
            	<th>KELUAR</th>
            	<th>KET.</th>
            	<th>Hadir</th>
            </tr>
            </thead>
            <tbody>
            <?php
				$kodeMk=""; 
				$n=0; foreach($mhs as $d): 
				$n++;
				if($kodeMk!=$d['matakuliah']){
					echo"<tr><th colspan='7'>$d[matakuliah]</th></tr>";
					$kodeMk=$d['matakuliah'];
				}
			?>
				<tr>
                	<td><?= $n ?></td>
                	<td><?= $d['mhs_nim']?></td>
                	<td><?= $d['Nama']?></td>
                	<td><?= $d['mhs_masuk']?></td>
                	<td><?= $d['mhs_keluar']?></td>
                	<td><?= ($u==1?Html::textinput("hadir[ket][$d[krs_id]]",(!$d['ket']?'-':$d['ket'])):(!$d['ket']?'-':$d['ket']))?></td>
                	<td><?= ($u==1?Html::radiolist("hadir[abs][$d[krs_id]]",[$d['mhs_stat']],[0=>'Tidak',1=>'Hadir']):$d['status'])?></td>
                </tr>	
			<?php endforeach;?>	
            </tbody>
        </table>
        <?= ($u==1?Html::submitButton('<i class="glyphicon glyphicon-search"></i> Ok', ['class' => 'btn btn-primary']):'')?>
    </div>
    <?php ActiveForm::end(); endif; ?>
</div>
