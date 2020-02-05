<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Funct;

$usr  = Yii::$app->user->identity;
$NAMA = Yii::$app->user->identity->name;
switch($usr->tipe){
	case 5 : $NAMA=Funct::profMhs(Yii::$app->user->identity->username,"Nama");break; 
	case 3 : $NAMA=Funct::Dosen(Yii::$app->user->identity->username);break; 
}
 


/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini">SIA</span><span class="logo-lg" style="font-size:16px">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Url::to("@web/images/default.jpg")?>" class="user-image" alt="User Image"/>
                            <span class="hidden-xs"><?= $NAMA ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Url::to("@web/images/default.jpg")?>" class="img-circle" alt="User Image"/>
                            <p><i>
                                <?= Yii::$app->user->identity->username ?>
                                </i>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!--
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    'Ganti Password',
                                    ['/site/change-password'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                                <!-- a href="#" class="btn btn-default btn-flat">Ganti </a -->
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
