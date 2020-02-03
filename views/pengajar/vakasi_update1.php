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


$this->title = 'Form Update Vakasi';
$this->params['breadcrumbs'][] = $this->title;

$MK="";$_MK=[];
$JR="";$_JR=[];

foreach($modJdw as $d){
	if(!isset($_MK[$d->jdwl_id])){
		$_MK[$d->jdwl_id]=1;
		$MK.=" ".$d->bn->mtk_kode." :".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
	}
}
$stat="";
if($modTrans->status==0){
	$stat="(DRAFT) ".(Funct::acc('/pengajar/vakasi-del-draft')?Html::a('<i class="fa fa-trash"></i> Hapus',['/pengajar/vakasi-del-draft','id'=>$modTrans->kode_transaksi],['class'=>'btn btn-primary']):"");
}


?>
<!--pre>
<?php print_r($p) ?>
</pre-->
<div class="panel panel-default">
	<div class="panel-heading">
    	<span class="panel-title"><b><?= "No Faktur: ".$modTrans->kode_transaksi." $stat" ?></b></span>
    </div>
    <div class="panel-body">
    	<table class="table table-bordered">
        	<tr>
            	<th width="50"> Bapak/Ibu Dosen</th>
                <th width="10"></th>
                <td><?= $model->bn->ds->ds_nm ?></td>
            </tr>
        	<tr>
            	<th width="50"> Jadwal</th>
                <th width="10"></th>
                <td><?= Funct::HARI()[$model->jdwl_hari].", $model->jdwl_masuk - $model->jdwl_keluar " ?></td>
            </tr>
        	<tr>
            	<th> Kode/Matakuliah</th>
                <th></th>
                <td><?= substr($MK,0,-1) ?></td>
            </tr>
        	<tr>
            	<th> Program Studi</th>
                <th></th>
                <td><?= substr($JR,0,-1) ?></td>
            </tr>        
        </table>

    	<?php
        if($que){
		?>
        <table class="table table-bordered">
        	<thead>
            <tr>
            	<th rowspan="2">&sum;Mahasiswa</th>
            	<th colspan="5">UTS</th>
            	<th colspan="5">UAS</th>
            </tr>
            <tr style="font-size:10px">
            	<th>&sum;TGS1</th>
            	<th>&sum;UTS</th>
                <th>&sum;NASKAH</th>
            	<th>&sum;SUSULAN</th>
            	<th>&sum;NASKAH SUSULAN</th>
            	<th>&sum;TGS2</th>
            	<th>&sum;UAS</th>
                <th>&sum;NASKAH</th>
            	<th>&sum;SUSULAN</th>
            	<th>&sum;NASKAH SUSULAN</th>
            </tr>
            <tr>
            	<th><?= $que['mhs']?:0 ?></th>
            	<th><?= $que['TGS1']?:0  ?></th>
            	<th><?= $que['UTS']?:0  ?></th>
            	<th><?= $que['NUTS']?:0  ?></th>
            	<th><?= $que['UTS1']?:0  ?></th>
            	<th><?= $que['NUTS1']?:0  ?></th>
            	<th><?= $que['TGS2']?:0  ?></th>
            	<th><?= $que['UAS']?:0  ?></th>
            	<th><?= $que['NUAS']?:0  ?></th>
            	<th><?= $que['UAS1']?:0  ?></th>
            	<th><?= $que['NUAS1']?:0  ?></th>
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
		//echo $modTrans->anv;
		
		echo $this->render('_vakasi_update1',[
			'mVakasi' => $mVakasi,
			'subAkses' => $subAkses,
			'modTrans'=>$modTrans,
			'p' => $p,
			'anv'=>($modTrans->anv==1?1:0),
		]) 
		
		?>
		</div>

        <div class="col-sm-6">
            <?php if(($modTrans->anv==1?1:0)==0): ?>
            <h4>Faktur</h4>
            <table class="table table-bordered">
            	<?php if(isset($p['UTS'])): ?>
            	<tr><th colspan="4">UTS</th></tr>
                <?= (isset($p['UTS']['TGS1'])?
					'<tr>
						<td>Tugas 1</td><td width="1">:</td>
						<td align="right">'.number_format($p['UTS']['TGS1']['q'],0,',','.')." x ".number_format($p['UTS']['TGS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($p['UTS']['TGS1']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($p['UTS']['UTS'])?
					'<tr>
						<td>UTS</td><td width="1">:</td>
						<td align="right">'.number_format($p['UTS']['UTS']['q'],0,',','.')."x".number_format($p['UTS']['UTS']['h'],0,',','.').'</td>
						<td align="right">'.number_format($p['UTS']['UTS']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UTS']['NUTS'])?
					'<tr>
						<td> Naskah Soal</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UTS']['NUTS']['q'],0,',','.')."x".number_format($_SESSION['UTS']['NUTS']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UTS']['NUTS']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UTS']['AWS1T'])?
					'<tr>
						<td> Honor Pengawas (PAGI)</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UTS']['AWS1T']['q'],0,',','.')." x ".number_format($_SESSION['UTS']['AWS1T']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UTS']['AWS1T']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UTS']['AWS2T'])?
					'<tr>
						<td> Honor Pengawas (SORE)</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UTS']['AWS2T']['q'],0,',','.')." x ".number_format($_SESSION['UTS']['AWS2T']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UTS']['AWS2T']['t'],0,',','.').'</td>
					</tr>'
					:'')
				?>
				<?php
				if(isset($_SESSION['TUTS'])){
					foreach($_SESSION['TUTS'] as $k=>$v){
						$produk = Funct::produk($k)->produk;
						$harga 	= Funct::produk($k)->hrg->harga;
						$total	= $v * $harga;
						
						echo"
						<tr>
							<td> $produk</td>
							<td>:</td>
							<td align='right'>".number_format($v,0,',','.')." x ".number_format($harga,0,',','.')."</td>
							<td align='right'>".number_format($total,0,',','.')."</td>
						</tr>";
					}
				
				}
				endif;
				
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
				if(isset($p['UAS'])):
				?>
            	<tr><th colspan="4">UAS</th></tr>
                <?= (isset($p['UAS']['TGS2'])?
					'<tr>
						<td>Tugas 2</td><td width="1">:</td>
						<td align="right">'.number_format($p['UAS']['TGS2']['q'],0,',','.')."x".number_format($p['UAS']['TGS2']['h'],0,',','.').'</td>
						<td align="right">'.number_format($p['UAS']['TGS2']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($p['UAS']['UAS'])?
					'<tr>
						<td>UAS</td><td width="1">:</td>
						<td align="right">'.number_format($p['UAS']['UAS']['q'],0,',','.')."x".number_format($p['UAS']['UAS']['h'],0,',','.').'</td>
						<td align="right">'.number_format($p['UAS']['UAS']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UAS']['B'])?
					'<tr style="text-align:right">
						<th align="right" colspan="3">Bonus</th>
						<td align="right">'.number_format($_SESSION['UAS']['B'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UAS']['NUAS'])?
					'<tr>
						<td>Naskah Soal</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UAS']['NUAS']['q'],0,',','.')."x".number_format($_SESSION['UAS']['NUAS']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UAS']['NUAS']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UAS']['AWS1'])?
					'<tr>
						<td>Honor Pengawas (PAGI)</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UAS']['AWS1']['q'],0,',','.')." x ".number_format($_SESSION['UAS']['AWS1']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UAS']['AWS1']['t'],0,',','.').'</td>
					</tr>'
					:'').
					(isset($_SESSION['UAS']['AWS2'])?
					'<tr>
						<td>Honor Pengawas (SORE)</td><td width="1">:</td>
						<td align="right">'.number_format($_SESSION['UAS']['AWS2']['q'],0,',','.')." x ".number_format($_SESSION['UAS']['AWS2']['h'],0,',','.').'</td>
						<td align="right">'.number_format($_SESSION['UAS']['AWS2']['t'],0,',','.').'</td>
					</tr>'
					:'')
				?>
				<?php
				if(isset($_SESSION['TUAS'])){
					foreach($_SESSION['TUAS'] as $k=>$v){
						$produk = Funct::produk($k)->produk;
						$harga 	= Funct::produk($k)->hrg->harga;
						$total	= $v * $harga;
						echo"
						<tr>
							<td>$produk</td>
							<td>:</td>
							<td align='right'>".number_format($v,0,',','.')." x ".number_format($harga,0,',','.')."</td>
							<td align='right'>".number_format($total,0,',','.')."</td>
						</tr>";
					}
				
				}
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
            <?php endif; ?>
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
			<?=
			Html::a(
				'Simpan Faktur',
				['/pengajar/vakasi-save','id'=>$modTrans->kode_transaksi],
				['class'=>'btn btn-primary','onclick'=>'return confirm("Periksa kembali inputan vakasi, Apakah data inputan sudah sesuai?")']
			).' '
			.(Funct::acc('/pengajar/vakasi-del')?Html::a('Hapus Data Transaksi',['/pengajar/vakasi-del'],['class'=>'btn btn-primary']):"")
			
			 
			?>            
        </div>
        
    </div>
</div>