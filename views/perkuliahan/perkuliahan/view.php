<?php
use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\tabs\TabsX;

$this->title = 'Absensi Perkuliahan Hari Ini';
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label'=>'<i class="fa fa-list"></i> Pulang Awal',
        'content'=>

         $this->render("/perkuliahan/perkuliahan/_pulang_awal",[
            'model' => $modPa,
        ]),
    ],
    [
        'label'=>'<i class="fa fa-list"></i> Ganti Dosen',
        'content'=>'B',
    ],
];

$status="(Dosen Belum Membuka Perkuliahan)";
if($model->ds_get_fid){
    $status="(Perkuliahan Sedang Berlangsung)";
    if($model->ds_stat==1){
        $status="(Perkuliahan Selesai)";
    }
}


?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"> Pertemuan ke-<?= $model->sesi." $status"?> </h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <tr>
                <th><?= $modDos->ds_nm ?></th>
                <th>
                    <?=
                    Funct::TANGGAL($model->tgl_ins)." | ".
                    Funct::HARI()[$model->jdwl_hari].", "
                    .substr($model->jdwl_masuk,0,5)
                    .'-'.substr($model->jdwl_keluar,0,5)?>
                </th>
            </tr>

            </thead>

        </table>

        <div class="col-sm-6">
            <h4>Info Matakuliah</h4>
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Matakuliah</th>
                    <th><i class="fa fa-users"></i></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n=0;$tmhs=0;
                foreach($modMtk as $d): $n++ ?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= "$d[mtk_kode]: $d[mtk_nama] ($d[jdwl_kls]) " ?></td>
                        <td><?= "$d[peserta]" ?></td>
                    </tr>
                <?php
                    $tmhs+=$d[peserta];
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr><th colspan="2">TOTAL</th><th><?= $tmhs ?></th></tr>
                </tfoot>
            </table>

        </div>

        <div class="col-sm-6">
            <?php
            if($model->ds_stat!='1'):
                echo TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false,
                    'bordered'=>true,
                    //'sideways'=>TabsX::POS_LEFT,
                ]);
            endif;
            ?>
        </div>



    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"> Absens Mahasiswa </h3>
    </div>
    <div class="panel-body">
        <?= $table ?>

    </div>


</div>