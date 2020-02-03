
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Kelola Absensi Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;

?>    
<?php Pjax::begin(['id'=>'pjaxAttendance','enablePushState' => false]); ?>
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
tbody tr:nth-child(even){
  background:#ECF0F1;
} 

.tb tbody tr:hover> td{background:#000;color:#fff;font-weight:bold;} 

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
<?= $table ?>

<?php Pjax::end(); ?>

<?php


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

$this->registerJs("$('.do_attendance').click(function () {
						var href = $(this);
						$.ajax({
						    url : 'save-absensi',
						    type: 'POST',
						    data : $(this).data(),
						    success: function(data, textStatus, jqXHR)
						    {
						    	data = jQuery.parseJSON(data);
					    		if (data.message !='') {
					    		 	alert(data.message);
					    		 	window.location.reload();
					    		}

					    		href.css({'color': data.color});
					    		href.attr('class', data.class);	
						    	 
						    },
						    error: function (jqXHR, textStatus, errorThrown)
						    {
						 	    alert('Error : ' + textStatus);
						 	    window.location.reload();
						    }
						});
				  });", View::POS_END, 'AttendanceFunction');
?>
