<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\models\User;
?>

<?php

//var_dump(\app\models\Funct::ak());
//var_dump(Yii::$app);
var_dump(Yii::$app->authManager->checkAccess(184,'/*'));

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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> <?= Yii::$app->user->identity->name ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
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
                <li><a href="<?= Url::to(['/site/index'])?>"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-education"></i> Akademik <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-book"></i> Matakuliah</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= Url::to(['/akademik/mtk'])?>"><i class="glyphicon glyphicon-book"></i> Group Matkul</a></li>
                                <li><a href="<?= Url::to(['/akademik/mtk'])?>"><i class="glyphicon glyphicon-book"></i> Matkul</a></li>
                                <li><a href="<?= Url::to(['/akademik/mtk'])?>"><i class="glyphicon glyphicon-book"></i> Paket Matakuliah</a></li>

                            </ul>
                        </li>

                        <li><a href="<?= Url::to(['/akademik/kr'])?>"><i class="glyphicon glyphicon-calendar"></i> Kurikulum</a></li>
                        <li><a href="<?= Url::to(['/akademik/kln'])?>"><i class="glyphicon glyphicon-calendar"></i> Kalender Akademik</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-tasks"></i> Penjadwalan</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= Url::to(['/akademik/ajr'])?>">Daftar Pengajar</a></li>
                                <li><a href="<?= Url::to(['/akademik/ajr'])?>">Absensi</a></li>
                                <li><a href="<?= Url::to(['/akademik/jdw'])?>">Jadwal Kuliah</a></li>
                                <li><a href="<?= Url::to(['/akademik/uts'])?>">Jadwal UTS</a></li>
                                <li><a href="<?= Url::to(['/akademik/uas'])?>">Jadwal UAS</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= Url::to(['/akademik/mhs'])?>"><i class="glyphicon glyphicon-user"></i> Mahasiswa</a></li>
                        <li><a href="<?= Url::to(['/akademik/dsn'])?>"><i class="glyphicon glyphicon-user"></i> Dosen Wali</a></li>
                        <li><a href="<?= Url::to(['/berita/baa', 'id'=>Yii::$app->user->id])?>"><i class="glyphicon glyphicon-exclamation-sign"></i> Berita</a></li>
                        
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-list"></i> Master<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= Url::to(['/master/fk'])?>"><i class="glyphicon glyphicon-screenshot"></i> Fakultas</a></li>
                        <li><a href="<?= Url::to(['/master/jr'])?>"><i class="glyphicon glyphicon-move"></i> Jurusan</a></li>
                        <li><a href="<?= Url::to(['/master/rg'])?>"><i class="glyphicon glyphicon-blackboard"></i> Ruangan</a></li>
                        <li><a href="<?= Url::to(['/master/ds'])?>"><i class="glyphicon glyphicon-user"></i> Dosen</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-wrench"></i> System<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= Url::to(['/user/index'])?>"><i class="glyphicon glyphicon-user"></i> User</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
