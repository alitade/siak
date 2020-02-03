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
$LULUS=$mhs->wisuda->predikat?"<b><i>(Mahasiswa Sudah Lulus)</i></b>":"";

#Pjax::begin(['enablePushState'=>FALSE]);
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
				$dataKR=[];
				foreach(\app\models\Kurikulum::find()->all() as $d){$dataKR[$d->kr_kode]=$d->kr_kode."-".$d->kr_nama;}
				
				/*
				$dataMHS=[];
				foreach(\app\models\Mahasiswa::find()->all() as $d){$dataMHS[$d->mhs_nim]=$d->mhs_nim."-".$d->mhs->people->Nama;}
				*/
                $krkd = null; 
                if(isset($_GET['Krs']['kurikulum'])){$krkd=$_GET['Krs']['kurikulum'];}
				
                echo $form->field($krs, 'kurikulum')->widget(Select2::classname(), [
					'data' => ($dataKR?$dataKR:[""]),
					'options' => ['placeholder' => 'Tahun Akademik'],
					'pluginOptions' => [
						'allowClear' => true
					],
				])->label(false);
				
                ?>
            </td>
            <td style="vertical-align:middle">
                <?php echo Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-search glyphicon-white"></i> Search'), ['class' => 'btn btn-primary']); ?>
            </td>
            <td rowspan="4"><center><br><!--?= Funct::getFoto(Yii::$app->user->identity->username,"Foto"); ?>--></center></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><input type="text" name="nim" class="form-control" id="nim" value="<?php echo @$_GET['nim']?>" /></td>
        </tr>
		<?php if(isset($_GET['Krs']['kurikulum']) and isset($_GET['nim'])  ):?>        
        <tr>
            <td>Nama</td>
            <td><?= Funct::profMhs(@$_GET['nim'],"Nama",$J)." $LULUS";?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?= @$jr->jr_jenjang."-".@$jr->jr_nama;?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= @$pr->pr_nama;?></td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td><?= Funct::nameWaliK(@$mhs->ds_wali,"ds_nm",$J);?></td>
        </tr>        
        <?php endif;?>
    </table>
</div>
</div>
<?php ActiveForm::end();
#Pjax::end();
?>


<?php
if($LULUS==""):
if($mhs){
	if(isset($_GET['Krs']['kurikulum']) ){
		$tahun = @$_GET['Krs']['kurikulum'];
		if($tahun!='NULL#NULL'){
		echo (!Funct::acc('/perwalian/tambah-krs')?"":
			Html::a(
				"<i class='glyphicon glyphicon-plus glyphicon-white'></i> Tambah Matakuliah",
				['perwalian/tambah-krs','Krs[kurikulum]' => $tahun,'nim'=>$_GET['nim']],
				['class'=>' btn btn-success']
			)
		)
	;
		
		echo "<br /><span style='color:red'>* T=Teori, P=Praktek, TP=Teori+Praktek</span>";
	echo "<br />";
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
						return $data['jdwl_id'].": ".Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
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
						return false ? "" : Html::a('<center><i class="glyphicon glyphicon-trash"></i></center>',[
                            "perwalian/delete-krs","id"=>$data["krs_id"],"kurikulum" => $_GET["Krs"]["kurikulum"],"nim" => @$_GET["nim"],]
                            ,['onclick'=>"return confirm('Apakah anda yakin akan menghapus data ini?')" ]
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
	}else{
	  echo '
			<div class="alert alert-danger" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only">Error:</span>
			  Tahun Akademik Belum Dipilih
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
}else{ echo "Data Mahasiswa Tidak Ada";}
endif;
?>
