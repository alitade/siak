<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'Kartu Hasil Studi (KHS)';
?>


<div class="panel panel-primary">
    <div class="panel-heading">Kartu Hasil Studi (KHS)</div>
    <div class="panel-body">
<table class='table table-hover table-condensed table-colored-header'>
    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,
        //'action' => Url::to(['mahasiswa/schedule-detail'])
        'method'=>'get'
        ]);
    ?>
        <tr><td style="vertical-align:middle">Tahun</td>
            <td style="vertical-align:middle">
            <?php
            $krkd = null; 
            if(isset($_GET['Krs']['kurikulum'])){
             $krkd=$_GET['Krs']['kurikulum'];
            }
                echo $form->field($model, 'kurikulum')->dropDownList(Funct::Kalender2(),
                        ['options' =>
                            [
                                $krkd => ['selected ' => true]
                            ]
                        ]
                    )->label('');
            ?>

            </td><td style="vertical-align:middle">
            <?php echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Search'), ['class' => 'btn btn-primary']); ?>
            
        </td></tr> 
        
        <tr><td class=cc>NIM</td><td class=cb><?=Yii::$app->user->identity->username?></td></tr>
        <tr><td class=cc>Nama</td>                   <td class=cb><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td></tr>
        <tr><td class=cc>Jurusan</td>                <td class=cb><?= $jr->jr_id."-".$jr->jr_nama;?></td></tr>
        <tr><td class=cc>Program Studi</td>          <td class=cb><?= $pr->pr_nama;?></td></tr>
        <tr><td>Pembimbing</td><td><?= Funct::nameWali($mhs->ds_wali,"ds_nm");?></td></tr>
        <?php ActiveForm::end();?>
        </table>

</div>
</div>

<?php
	echo "<!-- galih ";
	

	$TotSks=0;
	$TotMutu=0;
	//echo "kln ".@$Kalender->kr_kode;	
	$Kalender="";
	foreach($model2->getModels() as $d){
		$Kalender=$d['kr_kode_'];
		if(isset($NIL[$d['mtk_kode']])){
			$sks	=$NIL[$d['mtk_kode']][$d['krs_id']]['s'];
			if(!$sks){
				$sks=$NIL[$d['mtk_kode']][$Kalender]['s'];
				if(!$sks){$sks=$d['sks_'];}
			}
			$mutu	=$NIL[$d['mtk_kode']][$d['krs_id']]['x'];
			if(!$mutu){$mutu=$NIL[$d['mtk_kode']][$Kalender]['x'];}
			$TotSks +=$sks;	
			$TotMutu+=$mutu;
		}else{$TotSks +=$d['sks_'];} 
	}
	
	if($Kalender){
		if(substr($Kalender->kr_kode,0,1)==3){
			$TotSks=($TotSks<=0?0:($TotSks/2));
			//$TotMutu=($TotMutu<=0?0:($TotMutu/2));
		}
	}
	//echo $TotMutu." | ".$TotSks;
	echo"-->";
	
//if(isset($_GET['Krs'])){
	//$kr =explode('#',$_GET['Krs']['kurikulum']);

//}


?>

<br />
<?php
if(isset($_GET['Krs']['kurikulum'])){
    $kurikulum = $_GET['Krs']['kurikulum'];
    if($kurikulum!='NULL#NULL'){
		if($lunas){
		echo '<div style="color:red;font-size:16px"> <b>*Jika Ada Komponen Nilai Yang Kurang, Silahkan Hubungi Dosen Bersangkutan</b></div>';
		$dataProvider->pagination->pageSize=false;
		echo GridView::widget([
		'dataProvider' => $model2,
		'showPageSummary' => true,
		'filterModel' => false,
		
		'columns' => [
			['class' => 'kartik\grid\SerialColumn'],
			[
				'width'=>'20%',
				'value'=>function($data){return " Semester ".$data["mtk_semester"];},
				'group'=>true,  // enable grouping,
				'groupedRow'=>true,                    // move grouped column to a single grouped row
				'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
				'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
			],
			[
			 'header'  => 'Mata Kuliah',
			 'value' => function($data)use($NIL) {
				 
				$ket= $data["mtk_kode"]." - ".$data["mtk_nama"]; 
				if(!isset($NIL[$data['mtk_kode']])){
					if(is_array(Funct::ATT($data['krs_id']))){
						$ket.="<!-- ".Funct::absen2($data['jdwl_id'],$data['mhs_nim'])['persen']." --> <div  style='font-size:12px;color:red;font-weight:bold'>*".implode(", ",Funct::ATT($data['krs_id']))."</div>";
					}	
				} 
				return $ket;
			},
			 'format'  => 'raw',
			 'pageSummary'=>'Total'
		 ],
		/*
		[
			 'header'  => 'Absensi',
			 'value' => function($data)use($pr) {
						$kr =(int) substr($data['kr_kode_'],1,4);
						$absen=Funct::absen3($data['jdwl_id'],$data['krs_id']);
				 	//return $pr->pr_kode;
					
					if($kr < 1617){
						return (round(Funct::cekAbsen($data["krs_id"])/($pr->pr_kode==1?14:12)*100))."%";//$data["krs_grade"];	
						
					}
					
				 	$p=round($absen['persen'])."% ";
					//$p.=" (<sup>$absen[pertemuan]</sup>/<sub>$absen[kehadiran]</sub>)"
					;
					return $p;//$data["krs_grade"];
				},
			 'format'  => 'raw',
		 ],          
		#*/
		 [
			 'header'  => 'SKS',
			 'value' => function($data)use($NIL) {
				 $nil=$data["sks_"];
				 if(isset($NIL[$data['mtk_kode']])){
					 $nil = $NIL[$data['mtk_kode']][$data['krs_id']]['s'];
					 if(!$nil){
						 $nil=$NIL[$data['mtk_kode']][$data['kr_kode_']]['s'];
						 if(!$nil){$nil=$data["sks_"];}
					 }
					 
				 }
					//return $data["mtk_sks"];
					return $nil;
			  },
			 'format'  => 'raw',
			 'pageSummary'=>true
		 ],                 
		 [
			'header'  => 'Angka Mutu',
			'value' => function($data)use($NIL) {
				 $nil="-";
				 if(isset($NIL[$data['mtk_kode']])){
					 $nil = $NIL[$data['mtk_kode']][$data['krs_id']]['n'];
					 if(!$nil){
						 $nil=$NIL[$data['mtk_kode']][$data['kr_kode_']]['n'];	
						 if(!$nil){$nil='-';}
					 }
				 }
				 return $nil;
			},
			'format'  => 'raw',
		 ], 
		 [
			 'header'  => 'Huruf Mutu',
			 'value' => function($data) use($NIL) {
				 $nil="-";
				 if(isset($NIL[$data['mtk_kode']])){
					 $nil = $NIL[$data['mtk_kode']][$data['krs_id']]['h'];
					 if(!$nil){
						 $nil=$NIL[$data['mtk_kode']][$data['kr_kode_']]['h'];
						 if(!$nil){$nil='-';}
					 }
				 }
				 return $nil;
			 },
			 'format'  => 'raw',
		 ],
		 [
			 'header'  => 'Mutu',
			 'value' => function($data)use($NIL) {
				 $nil="-";
				 if(isset($NIL[$data['mtk_kode']])){
					 $nil = $NIL[$data['mtk_kode']][$data['krs_id']]['x'];
					 if(!$nil){
						 $nil=$NIL[$data['mtk_kode']][$data['kr_kode_']]['x'];
						 //if(!$nil){$nil='-';}
					 }
				 }
				 return $nil;
			 },
			 'format'  => 'raw',
			 'pageSummary'=>true
		 ],                                                      
		
		],
		'panel'=>[
		  'type'=>GridView::TYPE_PRIMARY,
		  'heading'=>'<i class="fa fa-navicon"></i> Matakuliah',
		  'footer'=>false,
		  'before'=>false,
		  'after'=>false
		],    
		'toolbar'=>false
		]); 
		
		

?>
        <table class='table table-hover table-condonsed table-colored-header' style="font-weight:bold;text-align:right">
            <tr><td class=cc>Index Prestasi </td>
            <td class=cb>
			<?= "$TotMutu/$TotSks = ".( $TotSks > 0 && $TotMutu > 0 ? number_format(($TotMutu/$TotSks),2):0)?>
            </td>
        	</tr>
        </table>
        <?php
		}else{
        echo "<center>
                <div style='color:red;font-weight:bold;font-size:16pt'> Silahkan Melunasi Tunggakan Kuliah Untuk Melihat Hasil Studi  </div>
            </center>";	
       	}
}else{
	  echo '
			<div class="alert alert-danger" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only">Error:</span>
			  Pilih Dahulu Tahun Akademiknya :)
			</div>
		';
    } 
}
?>
	