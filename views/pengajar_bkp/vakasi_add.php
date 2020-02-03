<?php

use app\models\Funct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;

$this->title = 'Form Vakasi';
$this->params['breadcrumbs'][] = $this->title;

$MK="";$_MK=[];
$JR="";$_JR=[];
foreach($All as $d){
	if(!isset($_MK[$d->bn->mtk_kode])){
		$_MK[$d->bn->mtk_kode]=1;
		$MK.=" ".$d->bn->mtk_kode.":".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=" ".$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
	}
}
if(!$_SESSION['kode']){echo"Data Matakuliah Belum Dipilih";}
else{
?>
<div class="panel panel-default">
	<div class="panel-heading">
    	<span class="panel-title"><?= "".$model->bn->ds->ds_nm." (".Funct::HARI()[$model->jdwl_hari] ." $model->jdwl_masuk - $model->jdwl_keluar)" ?></span>
    </div>
    <div class="panel-body">
    	<table class="table table-bordered">
        	<tr>
            	<th> Matakuliah</th>
                <td><?= substr($MK,0,-1) ?></td>
            </tr>
        	<tr>
            	<th> Jurusan</th>
                <td><?= substr($JR,0,-1) ?></td>
            </tr>        
        </table>

    	<?php
        if($que){
		?>
        <table class="table table-bordered">
        	<thead>
            <tr>
					<th valign="top" style="vertical-align:top"> &sum;Mahasiswa : <?= $que['mhs'] ?></th>
					<th> (&sum;UTS : <?= $que['UTS'] ?>) + (&sum;UTS Susulan : <?= $que['UTS1'] ?>) = <?= ($que['UTS']+$que['UTS1']) ?><br />
                    	Sisa <?= $que['mhs']-($que['UTS']+$que['UTS1']) ?>
                    </th>
					<th> (&sum;UAS : <?= $que['UAS'] ?>) + (&sum;UAS Susulan : <?= $que['UAS1'] ?>) = <?= ($que['UAS']+$que['UAS1']) ?><br />
						Sisa <?= $que['mhs']-($que['UAS']+$que['UAS1']) ?>
                    </th>
            </tr>
            </thead>
        </table>
        <?php	
		}
        ?>

    </div>
</div>


<div class="panel panel-primary">
	<div class="panel-heading">
    	<span class="panel-title"><?= $this->title ?></span>
    </div>
    <div class="panel-body">
		<div class="col-sm-6">
	    
        <?php 
		//foreach($sql as $d):
		echo $this->render('_vakasi_form',[
			'mVakasi' => $mVakasi,
			'subAkses' => $subAkses,
			'p' => $p,
			'anv'=>($modTrans->anv==1?1:0),
		]) 
		?>
		</div>

        <div class="col-sm-6">
            <h4>Faktur</h4>
            <table class="table table-bordered">
				<?php
				if(isset($_SESSION['UTS1'])):
				?>
                <tr><th colspan="4">UTS SUSULAN</th></tr>
                <?= (isset($_SESSION['UTS1']['UTS1'])?
					'<tr>
						<td>UTS Susulan</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UTS1']['UTS1']['q'],0,',','.')."x".number_format($_SESSION['UTS1']['UTS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UTS1']['UTS1']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UTS1']['NUTS1'])?
					'<tr>
						<td>Naskah Soal</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UTS1']['NUTS1']['q'],0,',','.')."x".number_format($_SESSION['UTS1']['NUTS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UTS1']['NUTS1']['t'],0,',','.').'</td>
					</tr>'
					:'')
				?>
                <?php 
				endif; 
				if(isset($_SESSION['UAS1'])):
				?>
                <tr><th colspan="4">UAS SUSULAN</th></tr>
                <?= (isset($_SESSION['UAS1']['UAS1'])?
					'<tr>
						<td>UAS Susulan</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UAS1']['UAS1']['q'],0,',','.')."x".number_format($_SESSION['UAS1']['UAS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UAS1']['UAS1']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UAS1']['NUAS1'])?
					'<tr>
						<td>Naskah Soal</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UAS1']['NUAS1']['q'],0,',','.')."x".number_format($_SESSION['UAS1']['NUAS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UAS1']['NUAS1']['t'],0,',','.').'</td>
					</tr>'
					:'')
				?>
                <?php endif; ?>
            	<tr><th colspan="3" style="text-align:right">Sub Total</th><td align="right"><?= "Rp.".number_format($subTot,0,',','.')	; ?></td></tr>
            	<tr><th colspan="3" style="text-align:right">PPH</th><td align="right"><?= "Rp.".number_format($pph,0,',','.')	; ?></td></tr>
            	<tr><th colspan="3" style="text-align:right">Total</th><td align="right"><?= "Rp.".number_format(($subTot+$pph),0,',','.')	; ?></td></tr>
            </table>
            <table class="table table-bordered">
            	<tr align="center"><th colspan="2">Mengetahui</th></tr>
                <tr>
                	<th><?= (Funct::acc('/pengajar/vksbaa')?"Kabag. BAAK":"Kajur.") ?></th>
                	<th>Kabag Keuangan.</th>
                </tr>
                <tr>
                	<td><?= $mVakasi->TANDA['kajur'] ?></td>
                	<td><?= $mVakasi->TANDA['keuangan'] ?></td>
                </tr>
            </table>
			<?= Html::a('Simpan Faktur',['/pengajar/vakasi-save1','id'=>$model->GKode],['class'=>'btn btn-primary']).' '
			.Html::a('Hapus Data Transaksi',['/pengajar/vakasi-del','id'=>$model->bn_id],['class'=>'btn btn-primary']) ?>            

        </div>
        
    </div>
</div>
<?php
}
?>
