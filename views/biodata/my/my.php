<?php
$this->title = "Profile";
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Funct;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;


$linkImg=Url::to('@web/pt/no_foto.jpg');
if(file_exists("../web/pt/".$model->photo) && $model->photo){$linkImg=Url::to("@web/pt/".$model->photo);}

$ctgl=explode(" ",$model->ctgl);
?>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 col-md-2">
                    <?= Html::img($linkImg,['class'=>'img img-thumbnail']) ?>
                </div>
                <div class="col-sm-10 col-md-10">
                    <table class="table table-bordered">
                        <tr><th width="200">Nama</th><td><?= $model->nama ?> </td></tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>
                                <?php
                                $jk="-";
                                if($model->jk=='1'||$model->jk=='L'){$jk="Laki-Laki";}
                                if($model->jk=='0'||$model->jk=='P'){$jk="Perempuan";}
                                echo $jk;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Agama</th>
                            <td><?= $model->agm->agama ?> </td>
                        </tr>
                        <tr><th>Tempat & Tanggal Lahir</th><td><?= $model->tempat_lahir.','.Funct::TANGGAL($model->tanggal_lahir,2)." <br><b>".Funct::USIA(@$model->tanggal_lahir)."</b>" ?></td></tr>
                        <tr>
                            <th>Alamat KTP</th>
                            <td>
                                <?php
                                $alamatC = explode("|",$model->alamat_ktp);
                                $alamatC[1]=' RT '.($alamatC[1]?:'-');
                                $alamatC[2]=' RW '.($alamatC[2]?:'-');
                                $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                                $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                                echo implode(", ",$alamatC);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Alamat Tinggal</th>
                            <td>
                                <?php
                                $alamatC = explode("|",$model->alamat_tinggal);
                                $alamatC[1]=' RT '.($alamatC[1]?:'-');
                                $alamatC[2]=' RW '.($alamatC[2]?:'-');
                                $alamatC[3]=' Kel/Des '.($alamatC[3]?:'-');
                                $alamatC[4]=' Kec '.($alamatC[4]?:'-');
                                echo implode(", ",$alamatC);
                                ?>
                            </td>
                        </tr>
                        <tr><th>No.Tlp.</th><td><?= $model->tlp ?></td></tr>
                        <tr><th>Email</th><td><?= Yii::$app->formatter->asEmail($model->email)  ?></td></tr>
                    </table>
                    <div class="pull-right"> <?= Html::a('<i class="fa fa-pencil"></i> Update '.($modTmp && $modTmp->status_data==1?"(Menunggu Konfirmasi Admin)":""),['/biodata/my-edt'],['class'=>'btn btn-primary']); ?> </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-10 col-md-10"><?= $LOG ?></div>
                <div class="col-sm-2 col-md-2 pull-right"><?= Html::a('<i class="fa fa-th-list"></i> Daftar Riwayat Akses',['/biodata/log-list','id'=>$model->id_],['target'=>'_blank'])?></div>
            </div>
        </div>
    </div>
<?php


