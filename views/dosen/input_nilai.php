
<style type="text/css"> 

.container{
  width:100%;
  margin:auto;
}
table{
  border-collapse:collapse;
  width:100%;
}
thead{
color: white;
background: #337AB7;
}
thead a{
	color: black;
}

tbody tr:nth-child(even){
  background:#ECF0F1;
} 
.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}
.scrollMore{
  margin-top:600px;
}
.up{
  cursor:pointer;
}

.summary{
  background: rgb(51, 122, 183) none repeat scroll 0% 0%;
  margin-left: 16px;
  margin-right: 16px;
  color: white;
  padding-top: 3px;
  padding-right: 10px;
  text-align: right;
  height: 70px;
}

.info{
  float: left;
  font-weight: bold;
  padding-left: 10px;
  text-align: left; 
}

</style>
   
<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

use app\models\Funct;
use app\models\BobotNilaiDosen;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\editable\Editable;
use kartik\export\ExportMenu;

$this->title = 'Input Nilai';
$this->params['breadcrumbs'][] = $this->title;

//echo print_r(Funct::StatLock(51));
$this->registerJs(';(function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap("<div class=\"container\" />");
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
            
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom){
                 $t_fixed.hide();
            }
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden")){
                 $t_fixed.show();
                 $("table").css("z-index","1");
                 $("table").toggle().toggle();
            }
              
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
         
      });
   };
})(jQuery);

$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $("html, body").animate({
      scrollTop: 0
   }, 2000);
 });
});', yii\web\View::POS_END, 'Sticky');


$Columns =[
        ['class' => 'yii\grid\SerialColumn'],
        [   //Attribute Ini Jangan di Hapus ya....   
            'width' => '5%',
            'label' => 'KRS ID',
            'attribute' => 'krs_id',
        ],
        [     
            'width' => '5%',
            'label' => 'NIM',
            'attribute' => 'mhs_nim',
        ], 
        [     
            'width' => 'auto',
            'label' => 'Mahasiswa',
            'attribute' => 'Nama',
        ], 
        [     
            'width' 	=> '5%',
            'header' 	=> 'Absensi',
			'value'		=>function($data){
				$abs = round(Funct::absen3($data['jdwl_id'],$data['krs_id'])['persen']);
				if($abs){
					return	$abs."%";
				}
				return "0%";
			}
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 1',
            'attribute' => 'krs_tgs1',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 2',
            'attribute' => 'krs_tgs2',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 3',
            'attribute' => 'krs_tgs3',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tambahan',
            'attribute' => 'krs_tambahan',
        ], 
        [     
            'width' => '5%',
            'label' => 'Quis',
            'attribute' => 'krs_quis',
        ], 
        [     
            'width' => '5%',
            'label' => 'UTS',
            'attribute' => 'krs_uts',
        ], 
        [     
            'width' => '5%',
            'label' => 'UAS',
            'attribute' => 'krs_uas',
        ],
        [
            'label' =>'Total',
            'attribute' => 'krs_tot',
            'value' => function($model){
                return (floatval($model['krs_tot']) < 1) ? 
                        number_format($model['total'],2)  : 
                        number_format($model['krs_tot'],2) ;
            }
        ],

        [
            'label' =>'Grade',
            'value' => function($model) use($header){
                return (floatval($model['krs_tot']) < 1) ? 
                        BobotNilaiDosen::Grade($model['total'],$header) : 
                        $model['krs_grade'] ;
            }
        ],
        [     
            'label' => 'Jurusan',
            'attribute' => 'jurusan',
			'format'=>'raw',
			'value'=>function($model){
				return "<span style='font-size:14px;font-weight:bold;'>  $model[mtk_kode] $model[mtk_nama] ($model[jdwl_kls])</span>";
			},
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
        ],
        
    ];

if($header['Lock']<32){
	$this->registerJs("$('#InputNilai').Tabledit({
		url: 'input-nilai',
		deleteButton: false,
		saveButton: true,
		restoreButton: false,
		autoFocus: true,
	
		hideIdentifier: false,
		buttons: {
			edit: {
				class: 'btn btn-sm btn-primary',
				html: '<span class=\"glyphicon glyphicon-pencil\"></span> &nbsp EDIT',
				action: 'edit'
			},
			 delete: {
				class: 'btn btn-sm btn-warning',
				html: '<span class=\"glyphicon glyphicon-refresh\"></span> &nbsp Default',
				action: 'default'
			}
		},
		columns: {
			identifier: [1, 'krs_id'],
			editable: ["
			.(array_key_exists('1',Funct::StatLock($header['Lock'])) ? "":"[5, 'krs_tgs1']," )
			.(array_key_exists('10',Funct::StatLock($header['Lock'])) ?"":"[6, 'krs_tgs2'],")
			.(array_key_exists('100',Funct::StatLock($header['Lock'])) ?"":"[7, 'krs_tgs3'],")
			."[8,'krs_tambahan'],"
			.(array_key_exists('1000',Funct::StatLock($header['Lock'])) ?"":"[9,'krs_quis'],")
			.(array_key_exists('10000',Funct::StatLock($header['Lock'])) ?"":"[10, 'krs_uts'],")
			.(array_key_exists('100000',Funct::StatLock($header['Lock'])) ?"":"[11, 'krs_uas'],")
			."]
		},
		onDraw: function() {
		   
		},
		onSuccess: function(data, textStatus, jqXHR) {
		   
		},
		onFail: function(jqXHR, textStatus, errorThrown) {
		  
		},
		onAlways: function() {
		   $.pjax.reload({container: '#InputNilai'});
		},
		onAjax: function(action, serialize) {
			if (action=='default') {
				$.pjax.reload({container: '#InputNilai'});
			}
		}
	});
		
	   ", yii\web\View::POS_END, 'InputNilai');
	
	
}
?>


<?php
$P= $header['nb_tgs1']+$header['nb_tgs2']+$header['nb_tgs3']+$header['nb_tambahan']+$header['nb_quis']+$header['nb_uts']+$header['nb_uas'];
$G= $header['B']+$header['C']+$header['D']+$header['E'];
if($P==0||$G==0){
	echo "$P==0||$G==0".'<div class="alert alert-danger"><h4>Bobot Nilai Masih Kosong, Klik '.Html::a('disini',['/dosen/bobot-nilai','id'=>$header['bn_id']],['class'=>'btn btn-primary']).' untuk mengatur bobot nilai</h4></div>';

}else{
	if($header['Lock']>=62){		
		echo '<div class="alert alert-success" role="alert"><b>Data Nilai Sudah Dimasukan Ke Transkrip. Jika Ada Perubahan Nilai, Silahkan Hubungi BAAK</b></div>';
	}
		
?>
<div class="panel panel-primary">
<div class="panel-heading">*Keterangan</div>
<div class="panel-body">
<b>
<ul>
	<!--
	<li> Penilaian Vakasi ditentukan berdasarkan jumlah: tugas 1, tugas 2, UTS dan UAS yang memiliki nilai.
    	 Jika tugas 1 / tugas 2 tidak ada, penilaian akan diganti dengan tugas 3 / quiz	
    </li>
	<li> Jika proses penginputan selesai dilakukan, harap tekan tombol <u><i style="color:#F00">Request Vakasi</i></u></li>
	-->   
	<li> Proses Penginputan nilai akan dikunci jika<!-- <i>Request vakasi</i> sudah dilakukan / --> data nilai sudah di pindahkan ke data transkrip / data vakasi sudah masuk ke dalam sistem</li>
	<li> Jika ada perubahan / perbaikan nilai, harap melapor ke BAAK</li>
	<li> Jika penginputan nilai telah selesai, berkas nilai asli harap di serahkan ke BAAK</li>
	<li> Vakasi dihitung berdasarkan jumlah total dari Tugas 1, UTS, Tugas 2, dan UAS yang terisi </li>
</ul>
</b>
	
</div>
<div class="panel-footer"><?= Html::a('<i class="fa fa-file-pdf"></i> Download Nilai',['nilai-pdf','id'=>$jdwl_id],['class'=>'btn btn-primary'])?></div>
</div> 
<?= 
GridView::widget([     
    'dataProvider'=> $dataProvider,
    //'pjax'=>true,
    'panel'=>[
        'type'=>GridView::TYPE_DEFAULT,
    ],
    'tableOptions'=>['id'=>'InputNilai'],
    'summary' => "<div class='summary' style='color:#000;'>
					<div class='info'>Kelola Nilai Mahasiswa: $header[ds_nm]<br />
						- Presentase Nilai: Tugas 1= $header[nb_tgs1]%, Tugas 2= $header[nb_tgs2]%, Tugas 3= $header[nb_tgs3]%, Absensi= $header[nb_tgs1]%, Quis= $header[nb_quis]%, UTS= $header[nb_uts]%, UAS= $header[nb_uas]% <br />
						- Range Nilai: A=100.00 - ".number_format(($header['B'] + 0.01),2).", B=".number_format($header['B'],2)." - ".number_format(($header['C']+ 0.01),2).",  C=".number_format($header['C'],2)." - ".number_format(($header['D']+ 0.01),2).", D=".number_format($header['D'],2)." - ".number_format(($header['E']+ 0.01),2).", E=".number_format($header['E'],2)." - 00.00
              </div> Total {totalCount} Mahasiswa </div>",
    'columns' => $Columns,
    'responsive'=>false,
    'hover'=>true,
    'export' => false,

]);
}
?>
