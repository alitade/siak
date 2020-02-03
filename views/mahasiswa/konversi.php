<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

$this->title = 'Index Pretasi Kumulatif (IPK)';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="panel panel-primary">
    <div class="panel-heading">Nilai Konversi</div>
    <div class="panel-body">
        <table class='table table-hover table-nomargin table-colored-header'>
                     
                <tr><td class=cc>NIM</td><td class=cb><?=Yii::$app->user->identity->username?></td></tr>
                <tr><td class=cc>Nama</td>                   <td class=cb><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td></tr>
                <tr><td class=cc>Jurusan</td>                <td class=cb><?= $jr->jr_id."-".$jr->jr_nama;?></td></tr>
                <tr><td class=cc>Program Studi</td>          <td class=cb><?= $pr->pr_nama;?></td></tr>
                <tr><td class=cc>Pembimbing Akademik</td>    <td class=cb><?= Funct::nameWali($mhs->ds_wali,"ds_nm");?></td></tr>
        </table>
</div>
</div>

<?php
	echo Html::a("<i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak IPK",array('mahasiswa/cetak-ipk'),array('class'=>' btn btn-info','target'=>'_blank'));
?>
<br /><br />
<?php
  Pjax::begin(
  ['enablePushState'=>FALSE]  
  ); 
  
  //$dataProvider->pagination->pageSize=false;
	echo GridView::widget([
	'dataProvider' => $model,
	'filterModel' => false,
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		[
			'width'=>'20%',
			'value'=>function($data){return " Semester ".$data["semester"];},
			'group'=>true,  // enable grouping,
			'groupedRow'=>true,                    // move grouped column to a single grouped row
			'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
			'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
		],
		[
		 'header'  => 'Mata Kuliah',
		 'value' => function($data) {
				return $data["kode_mk"]." - ".$data["nama_mk"];
			},
		 'format'  => 'raw',
		],
		[
		 'header'  => 'SKS',
		 'value' => function($data) {
				return $data["sks"];
				//return $data["sks_"];
			},
		 'format'  => 'raw',
		],                 
		[
		 'header'  => 'Angka Mutu',
		 'value' => function($data) {
				return Funct::mutu($data["huruf"]);//$data["krs_grade"];
			},
		 'format'  => 'raw',
		], 
		[
		 'header'  => 'Huruf Mutu',
		 'value' => function($data) {
				return $data["huruf"];
			},
		 'format'  => 'raw',
		],
/*		[
		 'header'  => 'Nilai Akhir',
		 'value' => function($data) {
				return $data["krs_tot"];
			},
		 'format'  => 'raw',
		],
*/		
		[
		 'header'  => 'Mutu',
		 'value' => function($data) {
				return Funct::Xmutu($data['huruf'],$data['sks']);
			},
		 'format'  => 'raw',
		],
	],
	'panel'=>[
	  'type'=>GridView::TYPE_PRIMARY,
	  'heading'=>'<i class="fa fa-navicon"></i> Index Pretasi Kumulatif (IPK)',
	],    
	'toolbar'=>false
	]); 
	Pjax::end();
?>
<table class='table table-hover table-nomargin table-colored-header'>
             
        <tr><td class=cc>Total SKS</td><td class=cb><?=//$sk['mtk_sks'];
		$sk['sks']?></td></tr>
		 <tr><td class=cc>Total Mutu</td><td class=cb><?=$mt['huruf']?></td></tr>
		  <tr><td class=cc>Index Prestasi Kumulatif</td><td class=cb><?php
			if($mt['huruf']>0 and 
			$sk['sks'] >0
			//$sk['sks_'] >0
			
			){
				echo $mt['huruf']/$sk['sks'];
		  }else{
		  echo 0;
		  }
		  ?></td></tr>
		</table>		
<script>
 /* $(document).ready(function(){   
    for (i=0;i<$('#sum_table tr:eq(0) td').length;i++) {
       var total = 0;
        $('td.rowDataSd:eq(' + i + ')', 'tr').each(function(i) {
           total = total + parseInt($(this).text());
        }); 
		$('#sum_table tr:last td').eq(i).text(total);
    }
	
$('#sum_table tr').each(function() {
    if (!this.rowIndex) return; // skip first row
    var sks = this.cells[0].innerHTML;
	var mutu = this.cells[1].innerHTML;
	var ipk = parseFloat(mutu)/parseFloat(sks);
	document.getElementById('ipk').innerHTML=ipk;
	
});
}); */


</script> 