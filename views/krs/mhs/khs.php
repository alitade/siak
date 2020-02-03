<?php
use Yii;
use yii\helpers\Html;

$this->title = 'KHS '.$model->mhs_nim;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$app=($mHkrs->app?:0);
#echo "KR : ".$mKr->kr_kode;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-tilte"> Hasil Studi Mahasiswa </span>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover table-condensed table-striped">
            <thead>
                <tr>
                    <th width="1px"> No </th>
                    <th> Kurikulum </th>
                    <th> Wali </th>
                    <th> &sum;Matkul </th>
                    <th> &sum;SKS </th>
                    <th> </th>
                </tr>

            </thead>
            <tbody>
            <?php
            $n=0;
            foreach ($modKhs as $d):$n++;?>
                <tr>
                    <td> <?= $n ?></td>
                    <td> <?= "<b>[$d->kr_kode]</b> ".$d->kr->kr_nama ?></td>
                    <td> <?= $d->ds->ds_nm ?></td>
                    <td> <?= Html::a('<i class="fa fa-eye"></i>',['#']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>
<p></p>

<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-tilte"> Index Prestasi Mahasiswa </span>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover table-condensed table-striped">
            <thead>
            <tr>
                <th width="1px"> No </th>
                <th> Semester </th>
                <th> &sum;Matkul </th>
                <th> &sum;SKS </th>
                <th> IP </th>
            </tr>

            </thead>
            <tbody>
            <?php
            $n=0;
            foreach ($modKhs as $d):$n++;?>
                <tr>
                    <td> <?= $n ?></td>
                    <td> <?= "<b>[$d->kr_kode]</b> ".$d->kr->kr_nama ?></td>
                    <td> <?= $d->ds->ds_nm ?></td>
                    <td> <?= Html::a('<i class="fa fa-eye"></i>',['#']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

