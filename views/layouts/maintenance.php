<?php

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


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<?php
	if(app\models\Funct::cekPassDef()){
		if(Yii::$app->controller->action->id!=='change-password'){
			return Yii::$app->response->redirect(Url::to(['site/change-password']));
		}
	}	
?>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Website Under Maintenance</title>
    <link href="<?= Url::to('@web/maintenance/css/bootstrap.css')?>" rel="stylesheet">
    <link href="<?= Url::to('@web/maintenance/css/font-awesome.css')?>" rel="stylesheet" />
    <link href="<?= Url::to('@web/maintenance/css/style.css')?>" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
</head>

<!-- ondragstart="return false" onselectstart="return false" oncontextmenu="return false" -->
<body class="back-section">
<?php $this->beginBody() ?>
<div class="overlay">
    <div class="col-lg-12 col-md-12 col-sm-12 head-line" >
        <div class="img">
            <img src="<?= Url::to("@web/maintenance/img/4.png")?>" width="20%">
        </div>

        Untuk sementara sistem informasi akademik USB YPKP tidak dapat diakses,
        dikarenakan sistem sedang dalam proses perbaikan
        <br>
        Mohon maaf untuk ketidaknyamanannya
        <!-- Sorry, Website <img src="<?= Url::to('@web/maintenance/img/maintenance.png')?>" class="img-circle img-custom" /> Under Maintenance -->
    </div>
    <!--div class="col-lg-12 col-md-12 col-sm-12 sub-line">
        Please check after some time, till the time you can enjoy a movie or cup of tea
    </div-->
    <!-- div class="container">
        <div class="row pad-top-two text-center" >
            <div class="col-lg-4 col-md-4 col-sm-4 social-div">
                <a href="#" ><i class="fa fa-facebook-square"></i></a>
                <a href="#" ><i class="fa fa-linkedin-square"></i></a>
                <a href="#" ><i class="fa fa-twitter-square"></i></a>
                <a href="#" ><i class="fa fa-github-square"></i></a>
                <br />
                <a href="http://binarytheme.com" style="color:#fff;font-size:16px;" target="_blank">By: binarytheme.com</a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 ">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 ">
                23/540 , New Street, New York,
                <br />
                United States.

            </div>
        </div>
    </div-->
</div>

<?php $this->endBody() ?>
</body>

</html>

<?php $this->endPage() ?>

