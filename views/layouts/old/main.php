<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\models\LoginForm;
use kartik\nav\NavX;
use kartik\widgets\Alert;
use kartik\widgets\Spinner;
use yii\bootstrap\Modal;

AppAsset::register($this);
$User = Yii::$app->user;

if(isset($_GET['d1r1th3h3'])){
	if($_GET['d1r1th3h3']==1){
		$_SESSION['huha']=1;
		return Yii::$app->response->redirect(Url::to(['site/index']));
	}
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<?php if ($User->isGuest OR  Yii::$app->controller->action->id === 'login'): ?>
<?php return Yii::$app->response->redirect(Url::to(['site/login']));?>
<?php else: ?>
<?php
	if(app\models\Funct::cekPassDef()){
		if(Yii::$app->controller->action->id!=='change-password'){
			return Yii::$app->response->redirect(Url::to(['site/change-password']));
		}
	}	
?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?=Yii::getAlias('@web').'/images/ypkp.png'?>">
    <link href="<?= Url::to('@web/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
                .navbar-inverse {
                    background-color: #337AB7;
                    border-color: #080808;
                    background-image: linear-gradient(to bottom, #337AB7, #337AB7);
                    background-repeat: repeat-x;
                    border: 1px solid #D4D4D4;
                }
                .navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:hover, .navbar-inverse .navbar-nav > .open > a:focus {
                    color: #FFF;
                    background-color: #1967BE;
                }
               
                .navbar .nav > li > a:focus, .navbar .nav > li > a:hover {
                    background-color: #158BF1;
                    color: #FFF;
                    text-decoration: none;
                }
                .navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus {
                    color: #FFF;
                    background-color: #1967BE;
                }
                .navbar .nav > li > a {
                    float: none;
                    padding: 15px;
                    color: #FFF;
                    text-decoration: none;
                    text-shadow: 0px 0px 0px #FFF;
                }
                .navbar-inverse .navbar-brand {
                    color: #FFF;
                }
                .carousel-control.left, .carousel-control.right {
                    background-image: none
                }
                .carousel-control {
                position: absolute;
                top: 0px;
                bottom: 0px;
                left: 0px;
                width: 15%;
                font-size: 20px;
                color: #FFF;
                text-align: center;
                text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.6);
                opacity: 0.5;
                }

                .dropdown-menu > li.kopie > a {
                    padding-left:5px;
                }
                 
                .dropdown-submenu {
                    position:relative;
                }
                .dropdown-submenu>.dropdown-menu {
                   top:0;left:100%;
                   margin-top:-6px;margin-left:-1px;
                   -webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;
                 }
                  
                .dropdown-submenu > a:after {
                  border-color: transparent transparent transparent #333;
                  border-style: solid;
                  border-width: 5px 0 5px 5px;
                  content: " ";
                  display: block;
                  float: right;  
                  height: 0;     
                  margin-right: -10px;
                  margin-top: 5px;
                  width: 0;
                }
                 
                .dropdown-submenu:hover>a:after {
                    border-left-color:#555;
                 }

                .dropdown-menu > li > a:hover, .dropdown-menu > .active > a:hover {
                  text-decoration: none;
                  background-color: #158BF1;
                  color: white;
                }  
                  
                @media (max-width: 767px) {

                  .navbar-nav  {
                     display: inline;
                  }
                  .navbar-default .navbar-brand {
                    display: inline;
                  }
                  .navbar-default .navbar-toggle .icon-bar {
                    background-color: #fff;
                  }
                  .navbar-default .navbar-nav .dropdown-menu > li > a {
                    color: red;
                    background-color: #ccc;
                    border-radius: 4px;
                    margin-top: 2px;   
                  }
                   .navbar-default .navbar-nav .open .dropdown-menu > li > a {
                     color: #333;
                   }
                   .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
                   .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
                     background-color: #ccc;
                   }

                   .navbar-nav .open .dropdown-menu {
                     border-bottom: 1px solid white; 
                     border-radius: 0;
                   }
                  .dropdown-menu {
                      padding-left: 10px;
                  }
                  .dropdown-menu .dropdown-menu {
                      padding-left: 20px;
                   }
                   .dropdown-menu .dropdown-menu .dropdown-menu {
                      padding-left: 30px;
                   }
                   li.dropdown.open {
                    border: 0px solid red;
                   }

                }
                 
                @media (min-width: 768px) {
                  ul.nav li:hover > ul.dropdown-menu {
                    display: block;
                  }
                  #navbar {
                    text-align: center;
                  }
                }  

    </style>
</head>
<!-- ondragstart="return false" onselectstart="return false" oncontextmenu="return false" -->
<body style="font-family: segoe ui;">
<?php $this->beginBody() ?>
<noscript>
<?php
echo'
	For full functionality of this page it is necessary to enable JavaScript. 
	Here are the <a href="http://www.enable-javascript.com" target="_blank"> instructions how to enable JavaScript in your web browser</a>
';
?>
</noscript>
<div class="wrap">

    <?php
		switch ($User->identity->tipe) {
			case 2:echo $this->render('menu_full',['User'=>$User]);break;
			case 3:echo $this->render('menu_dosen',['User'=>$User]);break;
			case 5:echo $this->render('menu_mhs',['User'=>$User]);break;
			case 6:echo $this->render('menu_rektor',['User'=>$User]);break;
			case 7:echo $this->render('menu_piket',['User'=>$User]);break;
			case 4:echo $this->render('menu_prodi',['User'=>$User]);break;
			case 8:echo $this->render('menu_baa',['User'=>$User]);break;
			case 12	:echo $this->render('menu_rektorat',['User'=>$User]);break;
			case 13 :echo $this->render('menu_keuangan',['User'=>$User]);break;
			case 14 :echo $this->render('menu_dirit',['User'=>$User]);break;
			case 15 :echo $this->render('menu_editor',['User'=>$User]);break;                    
			// sub baa
			case 18 :echo $this->render('menu_baa_sub',['User'=>$User]);break;
			// PA Erwan
			case 19 :echo $this->render('menu_mode1',['User'=>$User]);break;
			// ESBED
			case 20 :echo $this->render('menu_esbed',['User'=>$User]);break;
			// khusus magang :)
			case 17 :echo $this->render('menu_magang',['User'=>$User]);break;                    

			// DIRSIM PIKET
			case 21 :echo $this->render('menu_dirsim_piket',['User'=>$User]);break;                    

			case 22 :echo $this->render('menu_personalia',['User'=>$User]);break;                    
			default:break;
		  }
    ?>

    <div class="container">
    	<?php
		if($User->identity->tipe=='5'){
			if(!\app\models\Funct::FID($User->identity->username)){
				echo Alert::widget([
					'type' => Alert::TYPE_DANGER,
					'title' => 'Perhatian!!',
					'icon' => 'glyphicon glyphicon-ok-sign',
					'body' => '<b>Sehubungan dengan akan diberlakukannya  <i><u>"Fingerprint"</u></i> untuk absensi mahasiswa, 
						Maka mahasiswa dihimbau untuk segera registrasi <i><u>"ID Finger"</u></i> ke Direktorat Sistem Informasi dan Multimedia demi kelancaran proses perkuliahan</b>
						<br>*Notifikasi ini akan hilang jika mahasiswa telah melakukan registrasi <i><u>"Fingerprint"</u></i>
						',
					'showSeparator' => true,
					'delay' => false
				]);
			}

			if(\app\models\Funct::BOLEH($User->identity->username)>0){
				echo Alert::widget([
					'type' => Alert::TYPE_DANGER,
					'title' => 'KRS BERMASALAH!!',
					'icon' => 'glyphicon glyphicon-ok-sign',
					'body' => '<b>Jadwal perkuliahan di KRS bermasalah. Status Jadwal yang masih pending di krs tidak dapat terdeteksi sistem <i><u>"Fingerprint"</u></i> 
						silahkan konsultasi kepada <b>Ketua Program Studi</b> untuk merubah jadwal yang bentrok,
						jika sampai waktu perkuliahan status krs tetap pending maka mahasiswa di anggap tidak mengikuti perkuliahan tersebut.</b><br>
						*Pembukaan kembali krs online hanya berlaku bagi mahasiswa yang telah melakukan krs online 
						tgl. 07 september 2016 - 17 september 2016 serta terdapat jadwal yang bentrok dengan jadwal lainnya.
						',
					'showSeparator' => true,
					'delay' => false
				]);
			}
			if(\app\models\Funct::BENTROK($User->identity->username)>0){
				echo Alert::widget([
					'type' => Alert::TYPE_DANGER,
					'title' => 'KRS BERMASALAH!!',
					'icon' => 'glyphicon glyphicon-ok-sign',
					'body' => '
						<b>Jadwal perkuliahan di KRS BENTROK.
						Status Jadwal yang masih pending di krs tidak dapat terdeteksi sistem <i><u>"Fingerprint"</u></i> , Silahkan perbaiki pilihan matakuliah anda.<br>
						Penginputan KRS online dibuka kembali hingga tanggal 11 februari 2017 23:59						
						',
					'showSeparator' => true,
					'delay' => false
					//Jika sampai waktu perkuliahan status krs tetap pending maka mahasiswa di anggap tidak memilih perkuliahan tersebut.</b><br>
				]);
			}
		}

		if($User->identity->tipe=='21'){
			if(!\app\models\Funct::SIAP()){
				Modal::begin([
					'header'=>'<b>DATA PERKULIAHAN TIDAK ADA!!</b>',
					'options'=>[
						'class'=>'alert alert-danger'
					],
					'clientOptions'=>[
						'show'=>true,
						'backdrop'=>'static',
					],
					'closeButton'=>false,
				]);
				//echo Html::a('Sipakan Perkuliahan Perkuliahan Sekarang',['/dirit/siap'],['class'=>'btn btn-primary']);
				echo "<center>".Html::a('Sipakan Perkuliahan Sekarang',['/dirit/siap'],['class'=>'btn btn-primary','style'=>'align:center','id'=>'btnProccess'])."</center>";
				echo '<div class="well alert alert-success" id="btnLoad">';
					echo Spinner::widget(['preset' => 'large', 'align' => 'center']);
					echo '<div class="clearfix" style="text-align:center"><br><b>Data Sedang Diproses &hellip;</b></div>';
				echo '</div>';
				Modal::end();
			}

		}
		?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php require_once('alert.php'); ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Sistem Informasi Akademik Universitas Sangga Buana YPKP <?= date('Y') ?></p>

        <p class="pull-right">Developed By: <i><b><a href="">Internofa IT Solution</a></b></i></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<script>
<?php if(!isset($_SESSION['huha'])):?>
$('body').bind('copy',function(e) {
    e.preventDefault(); return false; 
})
;
<?php endif;?>
</script>
<script>
$(document).ready(function(){
	$("#btnLoad").hide();
	$("#btnProccess").click(function(){
		$("#btnLoad").show();
        $(this).hide();
    });	
});
</script>
<?php $this->endPage() ?>

<?php endif ?>