<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'Kartu Rancangan Studi (KRS)';
//$this->params['breadcrumbs'][] = $this->title;
?>


<?php 
Pjax::begin(['enablePushState'=>FALSE]);
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'method'=>'get',
    // 'enableAjaxValidation' => false,
    // 'enableClientValidation'=>true
    ]);

?>



<div class="panel panel-primary">
    <div class="panel-heading">Kartu Rancangan Studi (KRS)</div>
    <div class="panel-body">
    <table class='table table-hover table-condensed'>
        <tr>
            <td style="vertical-align:middle">Tahun Akademik</td>
            <td style="vertical-align:middle">
                <?php 
                $krkd = null; 
                if(isset($_GET['Krs']['kurikulum'])){
                 $krkd=$_GET['Krs']['kurikulum'];
                }
                
                
                
                echo $form->field($krs, 'kurikulum')->dropDownList(Funct::Kalender(),
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
            <td rowspan="4"><center><br><!--?= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?>--></center></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><?=Yii::$app->user->identity->username?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?= $jr->jr_id."-".$jr->jr_nama;?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= $pr->pr_nama;?></td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td><?= Funct::nameWali($mhs->ds_wali,"ds_nm");?></td>
        </tr>        
    </table>
</div>
</div>

<?php 
echo '<font color="red"> <b>*Jadwal bisa Sewaktu-waktu berubah, Jika ada jadwal yang tidak sesuai, silahkan hubungi Jurusan masing-masing</b></font>';
ActiveForm::end();

Pjax::end();
?>


<?php
echo"<!-- GALIH $ThnId";
echo Funct::CekTgl($ThnId);
echo"-->";
//echo Funct::BOLEH()."<br />";
if(isset($_GET['Krs']['kurikulum']) ){

	$tahun = @$_GET['Krs']['kurikulum'];
	if($tahun!='NULL#NULL'){
	#validasi tanngak untuk memunculkan fitur tambah krs
    echo 
	( 
		Funct::CekTgl($ThnId) 
		// validasi tambahan untukk develop
		|| ( Yii::$app->user->identity->username2=='alit')
		?
		Html::a(
			"<i class='glyphicon glyphicon-plus glyphicon-white'></i> Tambah Matakuliah",
			['mahasiswa/tambah-krs','Krs[kurikulum]' => $tahun],
			['class'=>' btn btn-success']
		)." ":""//date('Y-m-d')
	).
	Html::a(
		"<i class='glyphicon glyphicon-print'></i> Cetak KRS",
		['mahasiswa/krs-pdf','kurikulum'=>isset($_GET['Krs']['kurikulum']) 
		? $_GET['Krs']['kurikulum'] 
		: ""],
		['class'=>' btn btn-info','target'=>'_blank']);
    
	echo "<br /><span style='color:red'>* T=Teori, P=Praktek, TP=Teori+Praktek</span>";
echo "<br />";

  Pjax::begin(
  ['enablePushState'=>FALSE]  
  ); 
  echo GridView::widget([
        'dataProvider' => $model,
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
             'value' => function($data) {
                    return $data["mtk_kode"]." - ".$data["mtk_nama"];
                },
             'format'  => 'raw',
			 'pageSummary'=>'Total',
         ],
         [
             'header'  => 'Jenis',
             'value' => function($data) {
				 	$tipe=['Teori','Praktek','Teori + Praktek'];
                    return "<center>".(isset($tipe[$data["mtk_kat"]])?$tipe[$data["mtk_kat"]]:" ")."</center>";
                },
             'format'  => 'raw',
         ],
         [
             'header'  => 'SKS',
             'value' => function($data) {
                    return $data["mtk_sks"];
					//return $data["sks_"];
              },
             'format'  => 'raw',
			 'pageSummary'=>true
         ],                 
        
         [
             'header'  => 'Jadwal',
             'value' => function($data) {
					$jdwl=explode("|",$data['jadwal']);
					$jd="";
					$rg="";
					foreach($jdwl as $k=>$v){
						$Info=explode('#',$v);
						$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2] - $Info[3]";
						$rg.="$Info[4]<br />";
						$jd.=$ket."<br />";
					}
					$jdwl=$jd;
				 	return $jdwl;
                    //return Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
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
         [
             'header'  => 'Status',
             'value' => function($data) {
				 	if(Funct::Status()[$data["krs_stat"]]=='Approve'){
						return '<span class="label label-primary">'.Funct::Status()[$data["krs_stat"]].'</span>';
					}
					return '<span class="label label-warning">'.Funct::Status()[$data["krs_stat"]].'</span>';
                    //return Funct::Status()[$data["krs_stat"]];
                },
             'format'  => 'raw',
         ],                 
         [
             'header'  => 'Aksi',
             'value' => function($data)use($ThnId) {
					if(!Funct::acc('/mahasiswa/delete-krs')){return false;}			 
                    return (
						Funct::CekTgl($ThnId) || Funct::BOLEH()>0 
						|| ( Yii::$app->user->identity->username2=='alit')
						?
							$data["krs_stat"] == 1 ? "" 
							: Html::a('<center><i class="glyphicon glyphicon-trash"></i></center>',["mahasiswa/delete-krs","id"=>$data["krs_id"],"kurikulum" => $_GET["Krs"]["kurikulum"],'onclick'=>"return confirm('Apakah anda yakin akan menghapus data ini?')" ])
						:""
					);
                },
             'format'  => 'raw',
         ],                                                                       

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,    
        'panel'=>[
          'type'=>GridView::TYPE_PRIMARY,
          'heading'=>'<i class="fa fa-navicon"></i> Matakuliah Yang Diambil',
      ],    
    'toolbar'=>false
    ]); 
  Pjax::end();
}else{
  echo '
        <div class="alert alert-danger" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="sr-only">Error:</span>
          Tahun Akademik Belum Di pilih
        </div>
    ';  
}
?>
<br /><br />
<div class="col-lg-6">
<?php if(isset($_POST['Krs']['kurikulum'])) { ?>
    <table class='table table-hover table-condensed table-bordered'>
        <tr>
            <td>Total SKS Teori</td>
            <td>0</td>
        </tr>
		<tr>
            <td>Total SKS Praktek</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Total SKS Teori + Praktek</td>
            <td>0</td>
        </tr>
		<tr>
            <td>Total SKS</td>
            <td><?= $sks["sks"]." SKS";?></td>
        </tr>
	</table>
	<?php } ?>
</div>	
<?php
}

?>
