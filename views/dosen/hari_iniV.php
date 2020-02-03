<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Detail Absen Perkuliahan ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$tipe="[Jadwal Normal]";
$status="[DOSEN BELUM ABSEN MASUK!]";
if($model->tipe==1){$tipe="[Jadwal Pergantian]";}
if($model->ds_stat==='1'){$status="[PERKULIAHAN SELESAI]";}
else if($model->ds_stat==='2'){$status="[ABSEN KELUAR SEBELUM WAKTUNYA!]";}
else if($model->ds_stat==='0'){$status="[DOSEN TERLAMBAT MASUK!]";}
else{if(!$model->ds_masuk){$status="[DOSEN BELUM ABSEN MASUK!]";}else{if(!$model->ds_keluar){$status="[DOSEN BELUM ABSEN KELUAR!]";}}}

?>
<div class="panel panel-info">
    <div class="panel-heading"><h4 class="panel-title"><b><?= "[".\app\models\Funct::HARI()[$model->jdwl_hari].", ".substr($model->jdwl_masuk,0,5)." ".substr($model->jdwl_keluar,0,5)."] ".$model->dosen->ds_nm ?></b></h4></div>
    <div class="panel-body">
        <div class="col-xl-12" style="border: solid 1px #000;padding:2px;background: rgba(0,0,0,0.1)">
            <b><i>Untuk mengizinkan mahasiswa yang terlambat/tidak bisa absen masuk, klik tombol jam masuk/keluar mahasiwa, sehingga mahasiswa bisa melakukan finger keluar. Tombol ini tidak akan berfungsi jika perkuliahan sudah selesai.<br />Jika perkuliahan sudah selesai, akan muncul icon dikolom sesi yang berfungsi untuk mengubah kehadiran mahasiswa</i></b>
        </div>
        <?php if($mhs): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="3"><?= "$tipe ".($model->ds_masuk?substr($model->ds_masuk,0,5):"__")." - ".($model->ds_keluar?substr($model->ds_keluar,0,5):"__") ?> </th>
                <th colspan="3"><?= $status ?> </th>
            </tr>
            <tr>
                <th colspan="2" width="1%">NO</th>
                <th>NIM | NAMA</th>
                <th>ABSEN</th>
                <th style="background:<?=($model->ds_stat==1?"green;":'red;')?>"> Sesi <?= $model->sesi ?></th>
                <th>Ket.</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $jdId=""; $n=0;$n1=0;
            foreach($mhs as $d): $n++;
                if($d['jdwl_id']!=$jdId){$jdId=$d['jdwl_id'];echo"<tr><td colspan='6'><b>[$d[mtk_kode]] $d[mtk_nama] ($d[jdwl_kls])</b></td></tr>";$n1=0;}$n1++;
                $attribute="data-id='$d[id]' data-nim='$d->mhs_nim'";
            ?>
            <tr>
                <td><?= "$n" ?></td>
                <th><?= "$n1" ?></th>
                <td><?= "$d[mhs_nim]| $d[Nama]" ?></td>
                <td><?=
                    ($model->ds_stat==""&&$model->ds_masuk?
                        "<a href='javascript:;' class='do_time btn badge' $attribute style='background:".(($d->mhs_stat=="")&&$d->mhs_masuk?"green":"red").";'>".($d['mhs_masuk']?substr($d['mhs_masuk'],0,5):"__")."</a>":
                        ($d['mhs_masuk']?substr($d['mhs_masuk'],0,5):"__")
                    )." - ".($d['mhs_keluar']?substr($d['mhs_keluar'],0,5):"__")
                    ?>
                </td>
                <td>
                <?php
                $icon="";
                #$icon='<i class="fa fa-times-circle" style="color:red"></i>';
                if($model->ds_stat==='0'){
                    $icon='<i class="fa fa-times-circle" style="color:red"></i>';
                }else if($model->ds_stat==='1'){
                    $icon="<a href='javascript:;' class='do_abs btn fa fa-times-circle' $attribute style='color:red'></a>";
                    if($d->mhs_stat==='1'){$icon="<a href='javascript:;' class='do_abs btn fa fa-check-circle' $attribute style='color:green'></a>";}
                }
                echo $icon;
                ?>

                </td>
                <td class="ket_"><?= $d['ket'] ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <?php endif; ?>


    </div>

</div>
<?php
$this->registerJs("$('.do_time').click(function () {
    var href = $(this);
    $.ajax({
        url : 'save-berjalan-vt',
        type: 'POST',
        data : $(this).data(),
        success: function(data, textStatus, jqXHR)
        {
            data = jQuery.parseJSON(data);
            if (data.message !='') {
                alert(data.message);
                window.location.reload();
            }

            href.css({'background':data.background});
            href.attr('class', data.class);
            href.text(''+data.v);
            href.parent().parent().find('.ket_').text(''+data.ket);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error : ' + textStatus);
            window.location.reload();
        }
    });
});", View::POS_END, 'AttendanceFunction');


$this->registerJs("$('.do_abs').click(function () {
    var href = $(this);
    $.ajax({
        url : 'save-berjalan-v',
        type: 'POST',
        data : $(this).data(),
        success: function(data, textStatus, jqXHR)
        {
            data = jQuery.parseJSON(data);
            if (data.message !='') {
                alert(data.message);
                window.location.reload();
            }

            href.css({'color':data.color});
            href.attr('class', data.class);
            href.parent().parent().find('.ket_').text(''+data.ket);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error : ' + textStatus);
            window.location.reload();
        }
    });
});", View::POS_END, 'AbsFunction');

?>