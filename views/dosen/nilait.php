
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
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use app\models\BobotNilaiDosen;
use kartik\select2\Select2;
use kartik\editable\Editable;
use kartik\export\ExportMenu;

$this->title = 'Input Nilai';
$this->params['breadcrumbs'][] = $this->title;


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
				return $model['krs_grade'] ;

                return (floatval($model['krs_tot']) < 1) ? 
                        BobotNilaiDosen::Grade($model['total'],$header) : 
                        $model['krs_grade'] ;
            }
        ]
        
    ];
/*echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $Columns,
    'fontAwesome' => true,
    'dropdownOptions' => [
        'label' => 'Export All',
        'class' => 'btn btn-default'
    ]
]) . "<hr>\n";*/

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
        editable: [[5, 'krs_tgs1'],[6, 'krs_tgs2'],[7, 'krs_tgs3'],[8, 'krs_tambahan'],[9, 'krs_quis'],[10, 'krs_uts'],[11, 'krs_uas']]
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


if(
true
//$BFinal
){
	echo GridView::widget([     
		'dataProvider'=> $dataProvider,
		//'pjax'=>true,
		'tableOptions'=>['id'=>'InputNilai'],
	
		'summary' => "<div class='summary'><div class='info'>Kelola Nilai Mahasiswa || $header[mtk_kode] $header[mtk_nama] <br>
						$header[ds_nm] <br>
						<p style='font-size: 12px; color: rgb(155, 224, 236);'>KLASIFIKASI GRADE { 
						 <b> A=>( 100.00 - ".number_format(($header['B'] + 0.01),2)." ) ,
						 B => ( ".number_format($header['B'],2)." - ".number_format(($header['C']+ 0.01),2)." ) , 
						 C => ( ".number_format($header['C'],2)." - ".number_format(($header['D']+ 0.01),2)." ) , 
						 D => ( ".number_format($header['D'],2)." - ".number_format(($header['E']+ 0.01),2)." ) , 
						 E => ( ".number_format($header['E'],2)." - 00.00)</b> } </p>
					  </div> Menampilkan {begin} sampai {end} dari {totalCount} Mahasiswa || Halaman {page} dari {pageCount} Halaman</div>",
		'columns' => $Columns,
		'responsive'=>false,
		'hover'=>true,
		'export' => false,
	]); 
}else{
	echo 
		'<div class="alert alert-danger" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<span class="sr-only">Error:</span>
		'."<b>Bobot Nilai Matakuliah  $header[mtk_kode] $header[mtk_nama] ($header[jdwl_kls]) Belum Diset.</b>".'
		</div>';
}
?>  