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
    font-size: 10px;
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
#page {
  padding-top: 0px;
}
.grid-view {
  padding-top: 0px; 
}
article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
    display: none;
}
</style>
 <img src="<?php echo Url::to('@web/images/logo-big.png'); ?>" alt="" class='retina-ready' width="80" height="50"  class="thumbnail span3" style="display:inline; float: left; margin-right: 20px; width: 80px; height: 80px">
  <center><b>UNIVERSITAS SANGGA BUANA YPKP</b></center>
  <center>Jl PHH Mustopa No 68, Telp. (022) 7202233. Fax.7201756</center>
 <center>40124-Bandung, E-mail : mail@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</center>
<table class='table table-hover table-nomargin table-colored-header'>
             
        <tr><td class=cc>NIM</td><td class=cb><?=Yii::$app->user->identity->username?></td></tr>
        <tr><td class=cc>Nama</td>                   <td class=cb><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></td></tr>
        <tr><td class=cc>Jurusan / Fakultas</td>                <td class=cb><?= $jr->jr_id."-".$jr->jr_nama." / ".Funct::Fakultas($jr->fk_id);?></td></tr>
     	<tr><td class=cc>Pembimbing Akademik</td>          <td class=cb><?= Funct::nameWali($mhs->ds_wali,"ds_nm");?></td></tr>
		</table>

<?php

echo 
GridView::widget([
    'dataProvider' => $model,
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
                //return $data["sks_"];
              },
             'format'  => 'raw',
         ],                 
         [
             'header'  => 'Semester',
             'value' => function($data) {
                return $data["mtk_semester"];
              },
             'format'  => 'raw',
         ],                          
         [
             'header'  => 'Angka Mutu',
             'value' => function($data) {
                return Funct::mutu($data["krs_grade"]);//$data["krs_grade"];
              },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Huruf Mutu',
             'value' => function($data) {
                return $data["krs_grade"];
              },
             'format'  => 'raw',
         ],                     
         [
             'header'  => 'Nilai Akhir',
             'value' => function($data) {
                return $data["krs_tot"];
              },
             'format'  => 'raw',
         ],                     
         [
             'header'  => 'Mutu',
             'value' => function($data) {
                return Funct::Xmutu($data['krs_grade'],$data['mtk_sks']);
                //return Funct::Xmutu($data['krs_grade'],$data['sks_']);
              },
             'format'  => 'raw',
         ],
    ],
]);
?>

  <table class='table table-hover table-nomargin table-colored-header'>
             
        <tr><td class=cc>Total SKS</td><td class=cb><?=$sk['sks_']?></td></tr>
     <tr><td class=cc>Total Mutu</td><td class=cb><?=$mt['krs_grade']?></td></tr>
      <tr><td class=cc>Index Prestasi Kumulatif</td><td class=cb><?php
         if($mt['krs_grade']!= 0){
        echo $mt['krs_grade']/$sk['sks_'];
        }
     ?>

    </td></tr>
  </table>
