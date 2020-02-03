<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\models\User; 
use app\models\Funct;
?>

<div class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::to(['/site/index'])?>" style="font-size: 17px;">Dir. Sistem Informasi & Multimedia</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> <?= Funct::profMhs(Yii::$app->user->identity->username,"Nama")?><b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li style="width:270px;">
                            <center>
                            <div class="container" style="width:270px;">
                                <center><img src="<?= Url::to('@web/ypkp.png')?>" alt="..." class="thumbnail"></center>
                                <p><center><h5><?= Funct::profMhs(Yii::$app->user->identity->username,"Nama")?></h5></center></p>
                                <hr/>
                                <div class="pull-left">
                                    <?= Html::a('<i class="glyphicon glyphicon-edit"></i> Ganti Password', ['/site/change-password'],['class'=>'btn btn-primary'])?>
                                </div>
                                <div class="pull-right">
                                <?=Html::a('<i class="glyphicon glyphicon-log-out"></i> Keluar', ['/site/logout'],['class'=>'btn btn-danger',
                                    'data' => [
                                        'method' => 'post',
                                    ]
                                ])?>
                                </div>
                            </div>
                            </center>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="mailto:sim@usbypkp.ac.id'"><i class="glyphicon glyphicon-envelope"></i> sim@usbypkp.ac.id</a></li>
                <li class="active"><a href="<?= Url::to(['/site/index'])?>"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-education"></i> Mahasiswa<b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li><a href="<?= Url::to(['/mahasiswa/kartu-rancangan-studi'])?>"><i class="glyphicon glyphicon-file"></i> Kartu Rencana Studi (KRS)</a></li>
                        <li><a href="<?= Url::to(['/mahasiswa/kartu-hasil-studi'])?>"><i class="glyphicon glyphicon-list-alt"></i> Kartu Hasil Studi (KHS)</a></li>
                        <li><a href="<?= Url::to(['/mahasiswa/schedule'])?>"><i class="glyphicon glyphicon-tasks"></i> Jadwal</a></li>
                        <!-- <li><a href="<?= Url::to(['/mahasiswa/indek-prestasi-kumulatif'])?>"><i class="glyphicon glyphicon-certificate"></i> Indeks Prestasi Kumulatif (IPK)</a></li> -->
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-question-sign"></i> Bantuan<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="glyphicon glyphicon-download-alt"></i> Panduan KRS</a></li>
                    </ul>
                </li>
             </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>