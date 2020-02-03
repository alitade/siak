<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;

$this->title = 'Rekapitulasi Persensi Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border bg-aqua"><span style="font-size:16px;font-weight: bold;"><?= $this->title ?></span> </div>
    <div class="box-body">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th> Tanggal rekap </th>
                    <th> Kurikulum </th>
                    <th> Kelas </th>
                    <th> Periode </th>
                    <th> Tot. Revisi </th>
                    <th> Operator</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php
                        $tgl=explode(" ",$model->ctgl);
                        echo Funct::TANGGAL($tgl[0]).", ".substr($tgl[1],0,8);
                        ?>
                    </td>
                    <td> <?= $model->kr_kode.' - '.$model->kr->kr_nama ?> </td>
                    <td>
                        <?php
                        if($model->tipe=='0'){echo 'Pagi';}
                        else if($model->tipe=='1'){echo 'Sore';}
                        else{echo '-';}
                        ?>
                    </td>
                    <td> <?= Funct::TANGGAL($model->tgl_awal).' -  '.Funct::TANGGAL($model->tgl_akhir) ?> </td>
                    <td> <?= count($model->total) ?> </td>
                    <td> <?= $model->usr->name; ?> </td>
                </tr>
            </tbody>
        </table>
        <p></p>
        <div class="clearfix"></div>
        <?= Html::a("<i class='fa fa-download'></i> Unduh",['download-persensi-dosen','id'=>$model->id],['class'=>'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a("<i class='fa fa-eye'></i> Detail",['kehadiran-dosen-view','id'=>$model->id],['class'=>'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a("<i class='fa fa-exclamation'></i> Revisi",['kehadiran-dosen-rf','id'=>$model->id],['class'=>'btn btn-warning','target'=>'_blank']) ?>
        <?= Html::a("<i class='fa fa-trash'></i> Hapus",['hadir-dosen-del','id'=>$model->id],['class'=>'btn btn-danger','data-confirm'=>'Hapus Data Ini']) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border bg-aqua"><span style="font-size:16px;font-weight: bold;">Data Revisi</span> </div>
    <div class="box-body">
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th width="1%"> </th>
                    <th> Tanggal Revisi</th>
                    <th> Operator</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($dataProvider->getModels() as $d ): ?>
                    <tr>
                        <td><span class="text-nowrap">
                        <?= Html::a("<i class='fa fa-download'></i> Unduh Revisi",['download-persensi-dosen','id'=>$d->id],['class'=>'btn btn-xs btn-primary','target'=>'_blank']) ?>
                        <?= Html::a("<i class='fa fa-eye'></i> Detail Revisi",['kehadiran-dosen-view','id'=>$d->id],['class'=>'btn btn-xs btn-primary','target'=>'_blank']) ?>
                        <?= Html::a("<i class='fa fa-trash'></i> Hapus Revisi",['hadir-dosen-del','id'=>$d->id],['class'=>'btn btn-xs btn-danger','data-confirm'=>'Hapus Data Ini']) ?>
                        </span>
                        </td>
                        <td>
                            <?php
                            $tgl=explode(" ",$d->ctgl);
                            echo Funct::TANGGAL($tgl[0]).", ".substr($tgl[1],0,8);
                            ?>
                        </td>
                        <td> <?= $d->usr->name; ?> </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
    </div>

</div>