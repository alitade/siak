<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<?php 
use yii\helpers\Html;
/*use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use app\models\Funct;
use yii\grid\GridView;*/

use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\Kalender;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'INPUT KRS(Kartu Rencana Studi)';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-primary">
	<div class="panel-heading"><h4 class="panel-title"><?= $this->title?></h4></div>
    <div class="panel-body">
    	<table class="table table-bordered">
        	<tr>
            	<td width="180">Tahun Akademik</td>
            	<td width="1%">:</td>
                <td><?= $KR->kr_kode.'-'.$KR->kr_nama  ?></td>
            </tr>
        	<tr>
            	<td>NPM / Nama</td>
            	<td>:</td>
                <td><?= $MHS->mhs_nim." / ".$MHS->mhs->people->Nama  ?></td>
            </tr>
        	<tr>
            	<td>Jurusan / Program</td>
            	<td>:</td>
                <td><?= $MHS->jr->jr_jenjang.". ".$MHS->jr->jr_nama." / ".$MHS->pr->pr_nama ?></td>
            </tr>
        </table>    
    </div>
</div>

<?php


if(isset($mutu['krs_grade'])){		
		@$ipk=($mutu['krs_grade']?$mutu['krs_grade']/$k['sks_']:0);//;
		$ipk=floatval($ipk); 
		$maks=24;//;function_::SKSMaks(round($ipk,2));
		$sks_=$ada['mtk_sks'];
		$sksambil=$ambil['sks_'];		
}else{
			$ipk=NULL;
			$sksambil=$ambil['sks_'];
			if(isset($_GET['Krs']['kr_kode_']) and $ipk==NULL OR $ipk==0)
			{
				$maks=24;
			}else{
				$maks=0;
			}
			$sks_=$ada['mtk_sks'];
}
?>

<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'id'=>'create-krs',
    'action' => Url::to(['klsj/simpan-krs']),
    'method'=>'post'
    ]); ?>
<input type="hidden" name="kur" id="kur" value="<?=$_GET['Krs']['kurikulum']?>" />
<input type="hidden" name="ambil" id="ambil" value="<?=$maks?>" />
<input type="hidden" name="nim" id="nim" value="<?=$_GET['nim']?>" />

<?php
$th =  $_GET['Krs']['kurikulum'];
$nim =  $_GET['nim'];
  if($th!='NULL#NULL'){
    $thn = Kalender::find()->where(['kr_kode'=>$th])->one(); 
$db = Yii::$app->db1;
$query = "SELECT * FROM regmhs WHERE nim ='". $nim."' AND tahun='".$thn->kr_kode."'";
//print_r($query);die();
$hash = $db->createCommand($query)->queryOne();


if(
true
//$hash
){
  Pjax::begin(
  ['enablePushState'=>FALSE]  
  );
#          Funct::v($data);

  //$dataProvider->pagination->pageSize=false;
  echo GridView::widget([
        'dataProvider' => $data,
		'rowOptions'=>function($data){
			$dis = false;
			if( ($data['AvKrs']==0)){$dis=true;}

			if(Funct::cekKrs($data['jdwl_id'])==1){
				return ['class' => 'success','style'=>'font-weight:bold'];
			}else{
				if($dis){return ['class' => 'danger','style'=>'font-weight:bold'];}
			}
		},
        'id'=>'krs-grid',
        'filterModel' => false,
          'columns' => [
               ['class' => 'yii\grid\SerialColumn'],
               [
                  'width'=>'20%',
                  'value'=>function($data){
                        return " Semester ".$data["mtk_semester"];
                  },
                  'group'=>true,  // enable grouping,
                  'groupedRow'=>true,                    // move grouped column to a single grouped row
                  'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                  'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
               ],
               [
                  'header'  => 'Kode',
                  'value' => function($data) {
					  $mk=$data["mtk_kode"];
					  if($data['avKrsMk']==0){
						  $mk='<font><b>'.$data["mtk_kode"].'</b></font>';
					  }
                      return $mk;
                  },
                  'format'  => 'raw',
               ],
               [
                   'header'  => 'Matakuliah',
                   'value' => function($data) {
                      return $data["mtk_nama"].'<br><i class="fa fa-user"></i> '.$data['ds_nm'];
                    },
                   'format'  => 'raw',
               ],
				/*
               [
                   'header'  => 'Program',
                   'value' => function($data) {
                      return $data["pr_nama"];
                    },
                   'format'  => 'raw',
               ],                        
				*/
               [
                   'header'  => 'Jadwal',
                   'value' => function($data) {
						$jdwl=explode("|",$data['jadwal']);
						$jd = "";
						foreach($jdwl as $k=>$v){
							$Info=explode('#',$v);
								$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
								$jd .=$ket."<br />";
							}
						  return $jd;
					    
					  $jm=Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
					  if($data['avKrsTime']==0){
						  $jm='<font><b>'.$jm.'</b></font>';
					  }
                      return $jm;
                    },
                   'format'  => 'raw',
               ],
               [
                   'header'  => 'Kelas',
                   'value' => function($data) {
                      return $data["jdwl_kls"];
                    },
                   'format'  => 'raw',
               ], 
/*
               [
                   'header'  => 'Dosen',
                   'value' => function($data) {
                      return $data["ds_nm"];
                    },
                   'format'  => 'raw',
               ],
*/			   
               [
                   'header'  => 'SKS',
                   'value' => function($data) {
                      return $data["mtk_sks"];
                    },
                   'format'  => 'raw',
               ],
               [
                   'header'  => 'Tot',
                   'value' => function($data) {
                      return $data["tot"];
                    },
                   'format'  => 'raw',
               ],          
                [
                     'value' => function($data)use($MHS) {
                      $a = $data['jdwl_id'];
					  $dis = false;
					  
					  if($data['Ig']==0){
						  if( ($data['AvKrs']==0)){$dis=true;}
					  }
					  
                      return 
                      Html::checkbox('jdwl[]', Funct::cekKrs($data['jdwl_id'],$MHS->mhs_nim)==1 ? $data["jdwl_id"]:false, [
                            'value' => $data["jdwl_id"],'disabled'=>$dis,'id'=>'get'
                        ])
                      ."".Html::hiddenInput("sks[".$a."]",$data["mtk_sks"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ."".Html::hiddenInput("mtk[".$a."]",$data["mtk_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ."".Html::hiddenInput("mtk_nm[".$a."]",$data["mtk_nama"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ."".Html::hiddenInput("kr[".$a."]",$data["kr_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ."".Html::hiddenInput("nidn[".$a."]",$data["ds_nidn"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ."".Html::hiddenInput("ds_nm[".$a."]",$data["ds_nm"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                      ;
                  },
                     'format'  => 'raw',
                 ],         
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,    
        'panel'=>[
          'type'=>GridView::TYPE_PRIMARY,
          'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah '.$thn->kr->kr_nama,
          'after'=>Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Simpan', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave'])." ".
          Html::submitButton('<i class="glyphicon glyphicon-repeat"></i> Reset', ['class' => 'btn btn-info'],['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$th]),
          ],    
        'toolbar'=>false
    ]); 
  Pjax::end();
  }else{
      echo "<pre style='text-align: center;'><h4>Harap menghubungi Bagian Keuangan untuk melakukan registrasi.<br>Terimakasih.</h4></pre>";
  }
}else{
  echo '
        <div class="alert alert-danger" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="sr-only">Error:</span>
          Pilih Dahulu Tahun Akademiknya coyyy :)
        </div>
    ';  
}
?>
<?php ActiveForm::end();?>

