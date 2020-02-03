<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
use yii\grid\GridView;
?>
<script type="text/javascript">
              window.print();
            </script>
<style type="text/css">
body{
    font-size: 12px;
    font-family: Arial;
}
.navbar-fixed-bottom {
  display: none;
}
#footer{
  display: none;
}
.grid-view .summary {
  display: none;
}
.navbar-fixed-top .navbar-inner, .navbar-static-top .navbar-inner {
  display: none;
  -webkit-box-shadow: 0 1px 10px rgba(0,0,0,.1);
  -moz-box-shadow: 0 1px 10px rgba(0,0,0,.1);
  box-shadow: 0 1px 10px rgba(0,0,0,.1);
}
.panel {
  display: none;
  -webkit-box-shadow: rgba(0, 0, 0, 0.0470588) 0 1px 1px;
  background-color: #FFFFFF;
  border: 1px solid transparent;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  box-shadow: rgba(0, 0, 0, 0.0470588) 0 1px 1px;
  margin-bottom: 20px;
}
.detail-view th {
  text-align: left;
  width: 160px;
}
article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
    display: none;
}
body {
  font-size: 9px;
  font-family: times new roman;
}
link {
  display: none;
}
#page {
  padding-top: 0px;
}
.grid-view {
  padding-top: 0px; 
}

.table th, .table td {
  padding: 0px;
  line-height: -50px;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dddddd;
}
a #top{display:none;}

</style>
 <img src="<?php echo Url::to('@web/images/logo-big.png'); ?>" alt="" class='retina-ready' width="20px" height="20px"  class="thumbnail span3" style="display:inline; float: left; margin-right: 20px; width: 80px; height: 80px">
  <center><b>UNIVERSITAS SANGGA BUANA YPKP</b></center>
  <center>Jl PHH Mustopa No 68, Telp. (022) 7202233. Fax.7201756</center>
 <center>40124-Bandung, E-mail : mail@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</center>
 <table class='table table-hover table-nomargin table-colored-header'>
<tr>
<td class=cc>NIM </td><td td class=cc><strong><?php echo Yii::$app->user->identity->username; ?></strong></td>
<td class=cc>Nama</td><td class=cc><strong><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></strong></td>
</tr><tr>
<td class=cc>Jurusan </td><td class=cc><strong><?php echo $jr->jr_id."-".$jr->jr_nama; ?></strong></td>
<td class=cc>Program</td><td class=cc><strong><?php echo $pr->pr_nama; ?></strong></td>
</tr>
</table>


<?php

echo 
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => false,
    'columns' => [
         ['class' => 'yii\grid\SerialColumn'],
         [
             'header'  => 'Mata Kuliah',
             'value' => function($data) {
		            return $data["mtk_kode"]." - ".$data["mtk_nama"];
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
             'header'  => 'Semester',
             'value' => function($data) {
		            return "Semester ".$data["mtk_semester"];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jadwal UTS',
             'value' => function($data) {
		            return $data["jdwl_uts"];//Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jam',
             'value' => function($data) {
		            return $data["jdwl_uts"]." - ".$data["jdwl_uts_out"] ;//Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
	            },
             'format'  => 'raw',
         ],                                                                                                
         [
             'header'  => 'Ruangan',
             'value' => function($data) {
		            return $data['rg_kode'];
	            },
             'format'  => 'raw',
         ],                                                                                                         
         [
             'header'  => 'Kelas',
             'value' => function($data) {
		            return $data['jdwl_kls'];
	            },
             'format'  => 'raw',
         ],                                                                                                        
         [
             'header'  => 'Dosen',
             'value' => function($data) {
		            return $data['ds_nm'];
	            },
             'format'  => 'raw',
         ],                                                                                                        
    ],
]);

	?>