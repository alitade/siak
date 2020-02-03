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

$stat="";
if($modTrans->status==0){
	$stat="(DRAFT) ".Html::a('<i class="fa fa-trash"></i> Hapus',['/pengajar/vakasi-del-draft','id'=>$modTrans->kode_transaksi],['class'=>'btn btn-primary']) ;
}
$cetak="(Belum Dicetak)";
if($modTrans->cetak==1){$cetak="(Sudah Dicetak)";}

$this->title = "No Faktur: ".$modTrans->kode_transaksi." $cetak ";
$this->params['breadcrumbs'][] = $this->title;

$MK="";$_MK=[];
$JR="";$_JR=[];

foreach($modJdw as $d){
	if(!isset($_MK[$d->bn->mtk_kode])){
		$_MK[$d->bn->mtk_kode]=1;
		$MK.=$d->bn->mtk_kode.": ".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls.") ,";
	}
	if(!isset($_JR[$d->bn->kln->jr->jr_id])){
		$_JR[$d->bn->kln->jr->jr_id]=1;
		$JR.=$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.", ";
	}
}
?>
<!--pre>
<?php print_r($p) ?>
</pre-->
<div class="panel panel-default">
	<style>
	#td{font-size:13px}
	</style>
	<div class="panel-heading">
    	<span class="panel-title"><b><?= "No Faktur: ".$modTrans->kode_transaksi." $stat $cetak " ?></b></span>
    </div>
    <div class="panel-body">
    <table border="0" width="100%" height="100%" style="vertical-align:top;height:100%">
        <tr>
            <td width="50%" style="padding:5px" valign="top"> 
            &nbsp;&nbsp;ARSIP JURUSAN
            <?= $data."<br /><br />".$tanda1 ?>
            </td>
            <td width="1px" style="background:#000;border-left:#000 solid 1px">&nbsp;</td>
            <td width="50%" style="padding:5px">
            &nbsp;&nbsp;ARSIP KEUANGAN
            <?= $data."<br /><br />".$tanda2 ?>
            </td>
        </tr>
    </table>
    
    </div>
	<div class="panel-footer">
    	<span class="panel-title"><b><?=
		
			(Funct::acc('/pengajar/vakasi-cetak')?
			Html::a('<i class="fa fa-print"></i> Cetak Faktur',['/pengajar/vakasi-cetak','kd'=>$modTrans->kode_transaksi],(
				$modTrans->cetak==0?['class'=>'btn btn-success','onClick'=>"return confirm('Faktur yang sudah dicetak tidak bisa diubah kembali')"]: 
				['class'=>'btn btn-success'])
			):"") 
			#.' '.(Funct::acc('/pengajar/vakasi-update')?($modTrans->cetak==0?Html::a('<i class="fa fa-pencil"></i> Ubah Vakasi',['/pengajar/vakasi-update','id'=>$modTrans->kode_transaksi],['class'=>'btn btn-success']):""):"")
			#.' '.(Funct::acc('/pengajar/vakasi-revisi')?($modTrans->cetak==1?Html::a('<i class="fa fa-pencil"></i> Revisi Faktur',['/pengajar/vakasi-revisi','id'=>$modTrans->kode_transaksi],['class'=>'btn btn-success']):""):"")
			.' '.(Funct::acc('/pengajar/vakasi-delete')?($modTrans->status==0||$modTrans->cetak<=0?Html::a('<i class="fa fa-trash"></i> Hapus Faktur',['/pengajar/vakasi-delete','id'=>$modTrans->kode_transaksi],['class'=>'btn btn-success']):""):"")
			; 
		?></b></span>
    </div>
</div>