<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
$tgl_uts=explode(" ",$model->jdwl_uts);
$tgl_uas=explode(" ",$model->jdwl_uas);
#print_r($tgl_uts);

?>

<div class="col-sm-12">

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Matakuliah </th>
            <th>SKS</th>
            <th>UTS</th>
            <th>Ruang UTS</th>
            <th>UAS</th>
            <th>Ruang UAS</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= Funct::Mtk()[$model->bn->mtk_kode] ?></td>
            <td><?= $model->bn->mtk->mtk_sks ?></td>
            <td><?= $model->jdwl_uts ? Funct::HARI()[date('N',strtotime($tgl_uts[0]))].', '.Funct::TANGGAL($tgl_uts[0])." ".$tgl_uts[1]:" - " ?></td>
            <td><?= $model->rg_uts ?></td>
            <td><?= $model->jdwl_uas ? Funct::HARI()[date('N',strtotime($tgl_uas[0]))].', '.Funct::TANGGAL($tgl_uas[0])." ".$tgl_uas[1]:" - " ?></td>
            <td><?= $model->rg_uas ?></td>
        </tr>
        </tbody>
    </table>

    <h4>Jadwal Gabungan</h4>
    <table class="table table-bordered table-hover ">
        <thead>
        <tr>
            <th>#</th>
            <th>Jurusan</th>
            <th>Matakuliah </th>
            <th>SKS</th>
            <th>Ujian</th>
            <th>Tanggal</th>
            <th>Ruangan</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n=0;
        $tr="";
        foreach ($modGab as $d){
            $tgluts=explode(" ",$d->jdwl_uts);
            $tgluas=explode(" ",$d->jdwl_uas);
            $n++;
            echo"
            <tr>
                <td rowspan='2'>$n</td>
                <td rowspan='2'>".$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama." </td>
                <td rowspan='2'>".$d->bn->mtk->mtk_kode." ".$d->bn->mtk->mtk_nama."($d->jdwl_kls) </td>
                <td rowspan='2'>".$d->bn->mtk->mtk_sks."</td>
                <td>UTS</td>
                <td>".($d->jdwl_uts ? Funct::HARI()[date('N',strtotime($tgluts[0]))].', '.Funct::TANGGAL($tgluts[0])." ".$tgluts[1]:" - ")." </td>
                <td><b>".$d->rg_uts.":</b> ".$d->rguts->rg_nama." </td>
            </tr>
            <tr>
                <td> UAS </td>
                <td>".($d->jdwl_uas ? Funct::HARI()[date('N',strtotime($tgluas[0]))].', '.Funct::TANGGAL($tgluas[0])." ".$tgluas[1]:" - ")." </td>
                <td><b>".$d->rg_uts.":</b> ".$d->rguas->rg_nama." </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<div style="clear: both"></div>
