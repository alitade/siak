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

$this->title = 'KRS(Kartu Rencana Studi)';
$this->params['breadcrumbs'][] = $this->title;

if(isset($mutu['krs_grade'])){
	$ipk=$mutu['krs_grade']/$k['sks_'];
	$ipk=floatval($ipk); 
	$maks=24;//;function_::SKSMaks(round($ipk,2));
	$sks_=$ada['mtk_sks'];
	$sksambil=$ambil['sks_'];		
}else{
	$ipk=NULL;
	$sksambil=$ambil['sks_'];
	if(isset($_GET['Krs']['kr_kode_']) and $ipk==NULL OR $ipk==0)
	{$maks=24;
	}else{$maks=0;}
	$sks_=$ada['mtk_sks'];
}
?>

<!--
<div class="panel panel-primary">
  <div class="panel-heading">KRS (Kartu Rencana Studi)</div>
  <div class="panel-body">

   <table class='table table-hover table-nomargin table-colored-header'>
        <tr><td style="vertical-align:middle">Kode Kurikulum</td>
            <td style="vertical-align:middle">
      <?php $form = ActiveForm::begin([
          'type'=>ActiveForm::TYPE_VERTICAL,
          'id'=>'search-kurikulum',
          'method'=>'get'
          ]);
      ?>
      <?php 
      $krkd = null; 
      if(isset($_GET['Krs']['kurikulum'])){
       $krkd=$_GET['Krs']['kurikulum'];
      }
      echo $form->field($model, 'kurikulum')->dropDownList(Funct::Kalender("and kln_stat=1"),
                        ['options' =>
                            [
                                $krkd => ['selected ' => true]
                            ]
                        ]
                )->label('');           
            ?>
            </td>
       <td style="vertical-align:middle">
      <?php echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Search'), ['class' => 'btn btn-primary']); ?>
     </td>
        </tr>
    <tr>
    <td class=cc>IPK Semester Lalu</td>
    <td class=cc>
        <?php
        $ip = round($ipk,2);  
        echo Html::Input('text','', $ip,['id'=>'Maks','class'=>'form-control', 'width'=>30,'maxlength'=>30,'readOnly'=>true,'style'=>'width:45px']);?>
    </td>
    <td class=cc>SKS Maksimum</td>
    <td class=cc><?=Html::Input('text','', $maks,['id'=>'Maks','class'=>'form-control', 'width'=>30,'maxlength'=>30,'readOnly'=>true,'style'=>'width:45px']);?>
      </td>
    </tr>
    </table>

</div>
</div>

<?php ActiveForm::end();?>
-->

<?php 
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'id'=>'create-krs',
    'action' => Url::to(['mahasiswa/simpan-krs-v2']),
    'method'=>'post'
    ]); ?>
		<input type="hidden" name="kur" id="kur" value="<?=$_GET['Krs']['kurikulum']?>" />
		<input type="hidden" name="ambil" id="ambil" value="<?=$maks?>" />

<?php
$th =  $_GET['Krs']['kurikulum'];

if(
	true
	//($th!='NULL#NULL' && Funct::CekTgl($th))
	//|| Funct::BOLEH()>0 || (Yii::$app->user->identity->username2=='bowo' || Yii::$app->user->identity->username2=='alit' )
){
    $thn = Kalender::find()->where(['kln_id'=>$th])->one(); 
	$db = Yii::$app->db1;
	$query = "SELECT * FROM regmhs WHERE nim ='". Yii::$app->user->identity->username."' AND tahun='".$thn->kr_kode."'";
	$hash = $db->createCommand($query)->queryOne();
	//echo "<!-- kalendar ".$thn->kr->kr_nama." -->";
if(
//true
$hash 
){

echo '<font color="red"> <b>*Jadwal bisa Sewaktu-waktu berubah, Jika ada jadwal yang tidak sesuai, silahkan hubungi Jurusan masing-masing</b></font>';
  Pjax::begin(
  ['enablePushState'=>FALSE]  
  ); 
  
  //$dataProvider->pagination->pageSize=false;
  echo GridView::widget([
        'dataProvider' => $data,
		'rowOptions'=>function($data){
			$dis = false;
			if( ($data['avKrsTime']==0)||($data['avKrsMk']==0)||($data['avKrsJd']==0)){
				$dis=true;
			}

			if(Funct::cekKrs($data['jdwl_id'])==1){
				return ['class' => 'success','style'=>'font-weight:bold'];
			}else{
				if($dis){
					return ['class' => 'warning','style'=>'font-weight:bold'];
				}
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
                      return $data["mtk_nama"];
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
                   'header'  => 'Ruang',
                   'value' => function($data) {
                      return $data["rg_nama"];
                    },
                   'format'  => 'raw',
               ],          
                [
                     'value' => function ($data) {
                      $a = $data['jdwl_id'];
					  $dis = false;
					  if($data['Ig']==0){
						  if( ($data['avKrsTime']==0)||($data['avKrsMk']==0) ||($data['avKrsJd']==0) ){
							$dis=true;
						  }
					  }
					  
                      return 
                      Html::checkbox('jdwl[]', Funct::cekKrs($data['jdwl_id'])==1 ? $data["jdwl_id"]:false, [
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
          Data Tidak Ada
        </div>
    ';  
}
?>
<?php ActiveForm::end();?>
