<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\web\View;

$this->title = 'Detail Absensi Sesi'.$mJdwl->sesi;
?>
<style>
    .tb tbody tr:hover > td{background:#000;color:#fff;font-weight:bold;}
</style>
<div class="panel panel-primary" style="overflow: auto">
    <div class="panel-heading"><span class="panel-title">
        <?=
        $mJdwl->jdwl->bn->ds->ds_nm."<br>".
        app\models\Funct::HARI()[$mJdwl->jdwl->jdwl_hari].', '.$mJdwl->jdwl->jdwl_masuk.'-'.$mJdwl->jdwl->jdwl_keluar
        ." | Sesi $mJdwl->sesi "." | ".$mJdwl->jdwl->bn->mtk->mtk_kode.': '.$mJdwl->jdwl->bn->mtk->mtk_nama." (".$mJdwl->jdwl->jdwl_kls.")"
        ;
        ?>
        </span>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Status</th>
                <th>Pelaksana</th>
                <th>Waktu Pelaksanaan</th>
                <th>Keterangan</th>
            </tr>
            </thead>
            <tbody>
               <tr>
                   <td><?=($mJdwl[ds_stat]==1?'Hadir':'Tidak Hadir')?></td>
                   <td><?= $mJdwl->pengajar->ds_nm?></td>
                   <td><?= \app\models\Funct::TANGGAL($mJdwl['tgl_perkuliahan']).", ".substr($mJdwl[ds_masuk],0,5)."-".substr($mJdwl[ds_keluar],0,5) ?></td>
                   <td><?= $mJdwl->ket ?></td>
               </tr>
            </tbody>
        </table>
        <?= Html::a('Kembali ke halaman  Absensi',['/dosen/absensi','id'=>$mJdwl->jdwl_id],['class'=>'btn btn-success'])?>
        <?php if($mJdwl->ds_stat==1):?>
            <hr />
            <div class="col-sm-6">
                1)klik Icon Dibawah untuk merubah status kehadiran mahasiswa<br>
                2)klik Icon Dibawah untuk merubah keterangan kehadiran mahasiswa
            </div>
            <div class="col-sm-6">
                <div class="badge" style="background:red">Tidak Hadir</div>
                <div class="badge" style="background:green">Hadir</div>
                <b>[ N:NORMAL | A:TIDAK HADIR | I:IZIN | S:SAKIT | D:DISPEN | E:E-LEARNING ]</b>
            </div>
            <hr />
            <table class="table table-bordered tb" style="background: rgba(0,0,0,0.1)">
                <thead>
                <tr>
                    <th width="1px">No</th>
                    <th>NPM | NAMA</th>
                    <th>JAM</th>
                    <th>1)KEHADIRAN.</th>
                    <th>2)Ket.</th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $kodeMk="";
                $n=0; foreach($mhs as $d):
                    $hadir='<i class="glyphicon glyphicon-'.($d['stat']==1?'ok':'remove').'-circle" style="color:'.($d['stat']==1?'green':'red').'"></i>';
                    $n++;
                    if($kodeMk!=$d['jdwl_id']){
                        echo"<tr><th colspan='7'>$d[mtk_kode]: $d[mtk_nama] ($d[jdwl_kls]) </th></tr>";
                        $kodeMk=$d['jdwl_id'];
                    }
                    $bg="background:".($d['stat']==='1'? "green":"red").";";
                    $attribute = 'data-nim="'.$d['mhs_nim'].'" data-id="'.$d['id'].'"';
                    #$bg="";
                    ?>
                    <tr>
                        <td><?= $n ?></td>
                        <td><?= "<b>$d[mhs_nim] | </b> $d[Nama]"?></td>
                        <td><?= "$d[mhs_masuk]-$d[mhs_keluar]"?></td>
                        <td>
                            <?php $ketAbsen=[0=>'N',1=>'A',3=>'I',4=>'S',5=>'D',6=>'E'] ?>
                            <?='<a href="javascript:;" class="status btn badge" name ="ctrl_attendance" data-nim="'.$d['mhs_nim'].'"  data-id="'.$d['id'].'" data-nim="'.$d['mhs_nim'].'" style="font-weight:normal;'.$bg.'" >'.$ketAbsen[$d['kode']].'</a>'?>
                        </td>
                        <td>
                            <?php
                            $normal='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="N" data-k="0" '.$attribute.' style="font-weight:normal;">N</a>';
                            if($mJdwl->ds_masuk!=''){
                                $normal='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="N" data-k="0" '.$attribute.' style="font-weight:normal;" >N</a>';
                                if($d['mhs_masuk']==''){$normal='';}
                            }
                            ?>
                            <?= ""#($u==1?Html::radiolist("hadir[abs][$d[krs_id]]",[$d['stat']],[0=>'Tidak',1=>'Hadir']):$hadir)?>
                            <?= $normal ?>
                            <?='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="S" data-k="4" '.$attribute.' style="font-weight:normal;" >S</a>'?>
                            <?='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="I" data-k="3" '.$attribute.' style="font-weight:normal;" >I</a>'?>
                            <?='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="D" data-k="5" '.$attribute.' style="font-weight:normal;" >D</a>'?>
                            <?='<a href="javascript:;" class="keterangan btn badge" name ="ctrl_attendance" id="E" data-k="6" '.$attribute.' style="font-weight:normal;" >E</a>'?>
                            <?= $d['mhs_masuk']!=''&&$d['mhs_keluar']!=''?'':'<a href="javascript:;" class="keterangan btn badge" data-k="1" '.$attribute.' style="font-weight:normal;" >A</a>'?>
                        </td>
                        <td class="ket_"><?= ($u==1?Html::textinput("hadir[ket][$d[krs_id]]",(!$d['ket']?'-':$d['ket'])):(!$d['ket']?'-':$d['ket']))?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php
$this->registerJs("$('.status').click(function () {
    var href = $(this);
    $.ajax({
        url : 'save-sesi-kuliah',
        type: 'POST',
        data : $(this).data(),
        success: function(data, textStatus, jqXHR){
            data = jQuery.parseJSON(data);
            if(data.message !='') {alert(data.message);window.location.reload();}
            href.css({'background':data.background});
            href.attr('class', data.class);
            href.parent().parent().find('.ket_').text(''+data.ket);
        },
        error: function (jqXHR, textStatus, errorThrown){alert('Error : ' + textStatus);window.location.reload();}
    });
});", View::POS_END, 'StatusFunction');

$this->registerJs("$('.keterangan').click(function () {
    var href = $(this);
    $.ajax({
        url : 'save-sesi-kuliah-k',
        type: 'POST',
        data : $(this).data(),
        success: function(data, textStatus, jqXHR)
        {
            data = jQuery.parseJSON(data);
            if (data.message !='') {alert(data.message);window.location.reload();}
            href.parent().parent().find('.status').text(''+data.k);
        },
        error: function (jqXHR, textStatus, errorThrown){alert('Error : ' + textStatus);window.location.reload();}
    });
});", View::POS_END, 'KetFunction');

?>
