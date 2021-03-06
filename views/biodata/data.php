<?php
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
    <div class="panel-heading" >
        <span class="panel-title"> <?= $model->nama." ( $model->no_ktp )" ?></span>
        <div class="pull-right">
            <?=
            !Funct::acc('/biodata/akses')?'':Html::a('<i class="fa fa-lock"></i> Akses Kontrol',['biodata/akses','id'=>$model->id_],['class'=>'btn btn-primary btn-sm'])
            ?>
            <?=
            (
                !Funct::acc('/biodata/update')?'':(
                    Html::a('<i class="fa fa-pencil"></i> Update '.($model->tmp->status_data==1?"(Menunggu Konfirmasi Admin)":""),['/biodata/update','id'=>$model->id_],['class'=>'btn btn-primary btn-sm'])
                )
            )
            ?>
        </div>
        <p></p>
        <div class="clearfix" style="border-bottom: solid 1px #000"> </div>
        <div><b>
            Operator : <?= $model->c->name ." - ".Funct::TANGGAL($ctgl[0],2)." ".substr($ctgl[1],0,8)?>
            </b>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-2 col-md-2">
                <?= Html::img($linkImg,['class'=>'img img-thumbnail']) ?>
                <div class="clearfix"></div>
                <div class="pull-right">
                    <div style="margin-top: 2px;">
                        <?= ($model->photo?Html::a('<i class="fa fa-trash"></i> Hapus',['/biodata/del-pt','id'=>$model->id_],['class'=>'btn btn-primary btn-sm']):"") ?>
                        <?php
                        if(Funct::acc('/biodata/upload')){
                            Modal::begin([
                                'header'=>'Upload Foto Biodata',
                                'toggleButton' => [
                                    'label'=>'<i class="fa fa-upload"></i> Upload',
                                    'class'=>'btn btn-primary btn-sm'
                                ],
                            ]);
                            $form1 = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL,
                                'options'=>['enctype'=>'multipart/form-data',], 'action'=>\yii\helpers\Url::to(['upload','id'=>$model->id_]),
                                // important
                            ]);

                            echo $form1->field($model, 'photo')->widget(FileInput::classname(), [
                                'options' => ['multiple' => true, 'accept' => 'image/*'],
                                'pluginOptions' => [
                                    #'maxFileSize'=>256,
                                    'overwriteInitial'=>false
                                ]
                            ]);
                            ActiveForm::end();
                            Modal::end();
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-10 col-md-10">
                <?php
                if(Funct::acc('/dosen/view')){echo ($model->dsn->ds_id!==null?Html::a('Dosen',['/dosen/view','id'=>$model->dsn->ds_id],['class'=>'btn btn-success btn-sm']).' ' :"");}
                if(Funct::acc('/mahasiswa/view')){echo ($model->dsn->ds_id!==null?Html::a('Mahasiswa',['/dosen/view','id'=>$model->dsn->ds_id],['class'=>'btn btn-success btn-sm']).' ':"");}
                ?>
                <p></p>
                <table class="table table-bordered">
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

