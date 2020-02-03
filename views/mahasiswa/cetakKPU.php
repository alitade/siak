<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;
use yii\grid\GridView;
?>
<script type="text/javascript">
              window.print();
            </script>
<style type="text/css" media="print">
  @page {
  size: A4;
  
}

@media print {
  a {color: inherit !important; text-decoration: none !important;}
  nav {display: none;}
}
</style>

<style type="text/css">
body{
    font-size: 9px;
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
.vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
}
</style>
 
<div class="row">
    <div class="col-xs-2 col-md-2 ">
      <img src="<?php echo Url::to('@web/images/logo-big.png'); ?>" alt="" class='retina-ready' width="20px" height="20px"  class="thumbnail span2" style="display:inline; float: left; width: 80px; height: 80px">
    </div><!--
    -->
    <div class="col-xs-5 col-md-3 col-lg-3 vcenter"  style="float: left;">
        <h5>UNIVERSITAS SANGGA BUANA YPKP</h5>
    </div>
     <div class="col-xs-5 col-md-6 col-lg-4 vcenter" style="float: right;">
        <h4>KARTU PESERTA UJIAN <?=(strtolower($jenis)=='uts'?'TENGAH SEMESTER':'AKHIR SEMESTER') ?></h4>
        <h5>Semester : <?= $smt?></h5>
    </div>
</div>
<hr style="margin-top:6px;margin-bottom:6px"/>
<table width="100%">
	<tr valign="top">
    <td>
        <div class='col-sm-2'>
            <?= Html::img('@web/pt/'.Funct::profMhs(Yii::$app->user->identity->username,"pt"),['height'=>'100']); ?>
        </div>
    </td>
    <td>
	    <div class="col-sm-12">
        <table class='table table-hover table-nomargin table-colored-header'>
        <tr>
        	<td class=cc>NIM </td><td td class=cc><strong><?php echo Yii::$app->user->identity->username; ?></strong></td>
        	<td class=cc>Nama</td><td class=cc><strong><?php echo Funct::profMhs(Yii::$app->user->identity->username,"Nama");?></strong></td>
        </tr><tr>
        	<td class=cc>Jurusan </td><td class=cc><strong><?php echo $jr->jr_id."-".$jr->jr_nama; ?></strong></td>
        	<td class=cc>Program</td><td class=cc><strong><?php echo $pr->pr_nama; ?></strong></td>
        </tr>
        </table>    
        
    </td>
    </tr>
</table>
<hr style="margin-top:6px;margin-bottom:6px"/>

<div class="col-sm-12">
<?php
echo 
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => false,
    'columns' => [
         ['class' => 'yii\grid\SerialColumn'],
         [
             'header'  => 'MATA KULIAH',
             'value' => function($data) {
		            return $data["mtk_kode"]." - ".$data["mtk_nama"];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'DOSEN',
             'value' => function($data) {
                return $data['ds_nm'];
              },
             'format'  => 'raw',
         ],
         [
          'header' => 'JADWAL',
           'value' => function($data)use($jenis) {

                //return Funct::HARI()[$data['jdwl_hari']].', '.$data['jdwl_masuk'].' - '.$data['jdwl_keluar'];
				$hari	= '-';//$data['jdwl_hari'];
				$masuk	= '-';//$data['jdwl_masuk'];
				$keluar	= '-';//$data['jdwl_keluar'];
				
				//$data['jdwl_uas']='2017-01-07 08:00';
				//if( Yii::$app->user->identity->username == 'B1031571RT1003' ){

          $jadwal = 'BELUM DI SET';
					
					if(strtolower($jenis)=="uas" && !empty($data['jdwl_uas']) ){
						
            $hari	= date('N',strtotime($data['uas']));
            
            if (empty($data['uas'])) {
              $jadwal = 'BELUM DI SET';
            }else{
               $masuk = $data['uas_masuk'];
               $keluar = $data['uas_keluar'];
               $jadwal = Funct::HARI()[$hari].', '.$masuk.' - '.$keluar;
            }


					}
	
					if(strtolower($jenis)=="uts" && !empty($data['jdwl_uts']) ){
						
            $hari	= date('N',strtotime($data['jdwl_uts']));
						$masuk	= date('H:m',strtotime($data['jdwl_uts']));
						$keluar	= date('H:m',strtotime($data['jdwl_uts_out']));

            $jadwal = Funct::HARI()[$hari].', '.$masuk.' - '.$keluar;
					}
					


				//}
				
				return $jadwal;//Funct::HARI()[$hari].', '.$masuk.' - '.$keluar;
				//return $hari;

              },
         ],
         [
          'header' => '*Ttd',
          'value'   => function($data){
            return "";
          },
          'format'  => 'raw',
         ],
/*         [
          'header' => 'UAS',
          'value'   => function($data){
            return "";
          },
          'format'  => 'raw',
         ]
*/                                                                                                                
    ],
]);
?>
*Tanda Tangan Oleh Pengawas
</div>