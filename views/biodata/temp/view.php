<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\BiodataTemp $model
 */

$this->title = $model->id_;
$this->params['breadcrumbs'][] = ['label' => 'Biodata Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$linkImg="";#Url::to('@web/pt/no_foto.jpg');
if(file_exists("../web/pt/".$modBio->photo) && $modBio->photo){$linkImg=Url::to("@web/pt/".$modBio->photo);}

$linkImg1="";#Url::to('@web/pt/no_foto.jpg');
if(file_exists("../web/pt/".$model->photo) && $model->photo){$linkImg1=Url::to("@web/pt/".$model->photo);}

?>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title"> Perbaikan Biodata</span></div>
    <div class="panel-body">
        <table class="table table-bordered table-condensed" >
            <tr>
                <th>No KTP</th>
                <td>
                    <?= $modBio->no_ktp?:'-----------------' ?>
                    <i class="fa fa-arrow-right"></i>
                    <?= $model->no_ktp==$modBio->no_ktp?'':'<b>'.$model->no_ktp.'<br>' ?>
                </td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>
                    <?= $modBio->nama ?> <i class="fa fa-arrow-right"></i>
                    <?= $model->nama==$modBio->nama?'':'<b>'.$model->nama.'</b>' ?>
                </td>
            </tr>
            <tr>
                <th>Foto</th>
                <td>
                    <!--?= " $linkImg | $linkImg1"?-->
                    <div class="col-sm-2 col-md-2"><?= Html::img($linkImg,['class'=>'img img-thumbnail'])?></div>
                    <div class="col-sm-1 col-md-1" ><i class="fa fa-arrow-right"></i></div>
                    <?= ($linkImg==$linkImg1?"":'<div class="col-sm-2 col-md-2">'.Html::img($linkImg1,['class'=>'img img-thumbnail']).'</div>') ?>
                </td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>
                    <?php
                    $jk="-";
                    if($modBio->jk=='1'||$modBio->jk=='L'){$jk="Laki-Laki";}
                    if($modBio->jk=='0'||$modBio->jk=='P'){$jk="Perempuan";}
                    echo $jk;

                    $jk="-----------------";
                    if($model->jk=='1'||$model->jk=='L'){$jk="Laki-Laki";}
                    if($model->jk=='0'||$model->jk=='P'){$jk="Perempuan";}
                    echo ($model->jk === $modBio->jk ?" ":' <i class="fa fa-arrow-right"></i> <b>'.$jk.'</b>');

                    ?>
                </td>
            </tr>
            <tr>
                <th>Agama</th>
                <td>
                    <?= $modBio->agm->agama?:'-----------------' ?>
                    <i class="fa fa-arrow-right"></i>
                    <?= $model->agm->agama==$modBio->agm->agama?'':'<i class="fa fa-arrow-right"></i> <b>'.$model->agm->agama.'<br>' ?>
                </td>
            </tr>
            <tr><th>Tempat & Tanggal Lahir</th>
                <td>
                    <?= ($modBio->tempat_lahir?:"----------").','.Funct::TANGGAL($modBio->tanggal_lahir,2)." (<b>".Funct::USIA(@$modBio->tanggal_lahir)."</b>)" ?>
                    <i class="fa fa-arrow-right"></i>
                    <?php
                    if($modBio->tempat_lahir!=$model->tempat_lahir || $modBio->tanggal_lahir!=$model->tanggal_lahir){
                        echo ($model->tempat_lahir?:"").','.Funct::TANGGAL($model->tanggal_lahir,2)." (<b>".Funct::USIA(@$model->tanggal_lahir)."</b>)";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Alamat KTP</th>
                <td>
                    <?php
                    $alamatC = explode("|",$modBio->alamat_ktp);
                    $alamatC[0]=($alamatC[0]?:'-');
                    $alamatC[1]=' RT '.($alamatC[1]?:'-');
                    $alamatC[2]=' RW '.($alamatC[2]?:'-');
                    $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                    $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                    echo implode(", ",$alamatC);
                    ?> <i class="fa fa-arrow-right"></i>
                    <?php
                    if($model->alamat_ktp!=$modBio->alamat_ktp){
                        $alamatC = explode("|",$model->alamat_ktp);
                        $alamatC[0]=($alamatC[0]?:'-');
                        $alamatC[1]=' RT '.($alamatC[1]?:'-');
                        $alamatC[2]=' RW '.($alamatC[2]?:'-');
                        $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                        $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                        echo implode(", ",$alamatC);
                    }

                    ?>
                </td>
            </tr>
            <tr>
                <th>Alamat Tinggal</th>
                <td>
                    <?php
                    $alamatC = explode("|",$modBio->alamat_tinggal);
                    $alamatC[0]=($alamatC[0]?:'-');
                    $alamatC[1]=' RT '.($alamatC[1]?:'-');
                    $alamatC[2]=' RW '.($alamatC[2]?:'-');
                    $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                    $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                    echo implode(", ",$alamatC);
                    ?> <i class="fa fa-arrow-right"></i>
                    <?php
                    if($model->alamat_tinggal!=$modBio->alamat_tinggal){
                        $alamatC = explode("|",$model->alamat_tinggal);
                        $alamatC[0]=($alamatC[0]?:'-');
                        $alamatC[1]=' RT '.($alamatC[1]?:'-');
                        $alamatC[2]=' RW '.($alamatC[2]?:'-');
                        $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                        $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                        echo implode(", ",$alamatC);
                    }
                    ?>
                </td>
            </tr>
            <tr><th>No.Tlp.</th><td>
                    <?= $modBio->tlp?:"-----------" ?> <i class="fa fa-arrow-right"></i> <?= $model->tlp!=$modBio->tlp ? $model->tlp:'' ?>
                </td></tr>
            <tr><th>Email</th><td>
                    <?= $modBio->email?Yii::$app->formatter->asEmail($modBio->email):"----------" ?>
                    <i class="fa fa-arrow-right"></i>

                    <?= ($modBio->email!=$model->email? Yii::$app->formatter->asEmail($model->email):"")  ?>
                </td></tr>
            <tr>
                <td colspan="2"><?= ($model->status_data==1?Html::a('<i class="fa fa-save"></i> Konfirmasi Perubahan Data',['tmp-pro','id'=>$model->id_],['class'=>'btn btn-primary']):"") ?></td>
            </tr>

        </table>


    </div>

</div>