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

AppAsset::register($this);
$User = Yii::$app->user;
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
    <link href='https://fonts.googleapis.com/css?family=Sura' rel='stylesheet' type='text/css'>
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
<body style="font-family: segoe ui;">
<?php $this->beginBody() ?>
<div class="wrap">
	<?php echo $this->render('menut',['User'=>$User]);?>
    <div class="container">
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
<?php $this->endPage() ?>

<?php endif ?>