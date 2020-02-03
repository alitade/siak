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
    <div class="col-sm-12">
        <div class="col-sm-6">
        <table class="table">
            <tr><th>Dosen</th><td><?=$dosen['dosen']?></td></tr>
            <tr><th>Matakuliah</th><td><?=$dosen['matakuliah']?></td></tr>
            <tr><th>Jadwal (Durasi)	</th><td><?= 
				\app\models\Funct::HARI()[$dosen['jdwl_hari']].','.
				\app\models\Funct::TANGGAL($dosen['tgl_ins']).". $dosen[jadwal] ($dosen[durasi] Menit) "
			?></td></tr>
            <tr><th>Status</th><td><?=
				$dosen['status']
				.(strtoupper($dosen['status'])=='HADIR'?" ": ' '.Html::a('Anggap Hadir',['view','id'=>$id,'a'=>1],['class'=>'btn btn-success']))
				?></td></tr>
        </table>
        </div>
        
        <div class="col-sm-6">
        <table class="table">
            <tr><th>Pengajar</th><td><?=$dosen['pengajar']?></td></tr>
            <tr><th>Masuk</th><td><?=$dosen['ds_masuk']?></td></tr>
            <tr><th>Keluar</th><td><?=$dosen['ds_keluar']?></td></tr>
            <tr><th>Durasi</th><td><?=$dosen['durasi_dosen']?></td></tr>
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
			Html::a('Update',Url::to()."&u=1",['class'=>'btn btn-primary']):
			Html::a('Close Update',['view','id'=>$id],['class'=>'btn btn-primary'])
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
            <?php $n=0; foreach($mhs as $d): $n++?>
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
