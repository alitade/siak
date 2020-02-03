<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\models\User;

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> <?= Yii::$app->user->identity->name ?><b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li style="width:270px;">
                            <center>
                            <div class="container" style="width:270px;">
                                <center><img src="<?= Url::to('@web/ypkp.png')?>" alt="..." class="thumbnail"></center>
                                <p><center><h5><?= Yii::$app->user->identity->name ?></h5></center></p>
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
                <li class="active"><a href="#"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-education"></i> Akademik <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= Url::to(['/prodi/mtk'])?>">Matakuliah</a></li>
                        <li><a href="<?= Url::to(['/prodi/kln'])?>">Kalender Akademik</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Penjadwalan</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= Url::to(['/prodi/ajr'])?>">Daftar Pengajar</a></li>
                                <li><a href="<?= Url::to(['/prodi/jdw'])?>">Jadwal Kuliah</a></li>
                                <li><a href="<?= Url::to(['/prodi/uts'])?>">Jadwal UTS</a></li>
                                <li><a href="<?= Url::to(['/prodi/uas'])?>">Jadwal UAS</a></li>
                                <li><a href="<?= Url::to(['/prodi/kehadiran-dosen'])?>">Kehadiran Dosen</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= Url::to(['/prodi/mhs'])?>">Mahasiswa</a></li>
                        <li><a href="<?= Url::to(['/prodi/dsn'])?>">Dosen Wali</a></li>
                        
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>