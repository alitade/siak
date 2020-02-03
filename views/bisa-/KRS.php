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
Pjax::begin(
    [
        'enablePushState'=>FALSE
    ]
);
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
                
                
                
                /*echo $form->field($krs, 'kurikulum')->dropDownList(Funct::KalenderB(),
                            ['options' =>
                                [
                                    $krkd => ['selected ' => true]
                                ]
                            ]
                    )->label('');*/           
                ?>
            </td>
            <td style="vertical-align:middle">
                <?php echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Search'), ['class' => 'btn btn-primary']); ?>
            </td>
            <td rowspan="4"><center><br><!--?= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?>--></center></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><input name='nim' id='nim' type="text" /></td>
        </tr>
    </table>
</div>
</div>

<?php ActiveForm::end();
Pjax::end();
?>


<?php
if(isset($_GET['Krs']['kurikulum']) ){

	$tahun = @$_GET['Krs']['kurikulum'];
	if($tahun!='NULL#NULL'){
    echo 
	( Funct::CekTgl($ThnId)?
		Html::a(
			"<i class='glyphicon glyphicon-plus glyphicon-white'></i> Tambah Matakuliah",
			['mahasiswa/tambah-krs','Krs[kurikulum]' => $tahun],
			['class'=>' btn btn-success']
		)." ":" "
	).
	Html::a(
		"<i class='glyphicon glyphicon-print'></i> Cetak KRS",
		['mahasiswa/cetak-krs','kurikulum'=>isset($_GET['Krs']['kurikulum']) 
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
        'filterModel' => false,
          'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
         ],
         [
             'header'  => 'Jenis',
             'value' => function($data) {
                    return "<center>".$data["mtk_jenis"]."</center>";
                },
             'format'  => 'raw',
         ],
         [
             'header'  => 'SKS',
             'value' => function($data) {
                    return $data["mtk_sks"];
                },
             'format'  => 'raw',
         ],                 
        
         [
             'header'  => 'Jadwal',
             'value' => function($data) {
                    return Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
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
                    return Funct::Status()[$data["krs_stat"]];
                },
             'format'  => 'raw',
         ],                 
         [
             'header'  => 'Aksi',
             'value' => function($data) {
                    return $data["krs_stat"] == 1 ? "" : Html::a('<center><i class="glyphicon glyphicon-trash"></i></center>',["mahasiswa/delete-krs","id"=>$data["krs_id"],"kurikulum" => $_GET["Krs"]["kurikulum"],'onclick'=>"return confirm('Apakah anda yakin akan menghapus data ini?')" ]);
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
          Pilih Dahulu Tahun Akademiknya coyyy :)
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
