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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> 
					<?= app\models\Funct::Dosen(Yii::$app->user->identity->username) ?>
                    <b class="caret"></b>
                    </a>
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
                <li class="active"><a href="<?= Url::to(['/site/index'])?>"><i class="glyphicon glyphicon-home"></i> Beranda</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-education"></i> Dosen<b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li><a href="<?= Url::to(['/dosen/bobot'])?>"><i class="glyphicon glyphicon-book"></i> Bobot Nilai</a></li>
                        <li><a href="<?= Url::to(['/dosen/jdwl'])?>"><i class="glyphicon glyphicon-list"></i> Jadwal Mengajar</a></li>
                        <li><a href="<?= Url::to(['/dosen/perkuliahan'])?>"><i class="glyphicon glyphicon-list"></i> Absensi Hari Ini</a></li>
                        <!-- <li><a href="<?= Url::to(['/dosen/krs'])?>"><i class="glyphicon glyphicon-file"></i> KRS Mahasiswa</a></li>-->
                    </ul>
                </li>
                <li><a href="<?= Url::to(['/manual-dosen.pdf'])?>" target="_blank"><i class="glyphicon glyphicon-file"></i> Panduan (R.1)</a></li>
                <!-- <li><a href="<?= Url::to(['/dosen/perkuliahan'])?>" target="_blank"><i class="glyphicon glyphicon-file"></i> Absensi</a></li>-->
        </div><!--/.nav-collapse -->
    </div>
</div>