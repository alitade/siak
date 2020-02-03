<?php
use yii\helpers\Html;
use kartik\widgets\SwitchInput;

$this->title='Pengaturan';
$this->params['breadcrumbs'][] = $this->title;

$arrNil=[3,4,5,6,7,8,9,10];$arrNil=array_flip($arrNil);
$arrP=[3,4,5,6];$arrP=array_flip($arrP);
$arrG=[7,8,9,10];$arrG=array_flip($arrG);

$arrAbs = [11,12,13,14,15,16,17,18,19];$arrAbs=array_flip($arrAbs);
$arrDs  = [11,12,13,14];$arrDs=array_flip($arrDs);
$arrMhs = [15,16,17,18,19];$arrMhs=array_flip($arrMhs);

$arrS=[1,2];$arrS=array_flip($arrS);

$dNilai='<table class="table table-bordered"><thead><tr><th>Kode</th><th>Nilai</th><th>Ket.</th><th><i class="fa fa-gears"></i></th></tr></thead>';
$data='<table class="table table-bordered"><thead><tr><th>Ket.</th><th>Nilai</th><th><i class="fa fa-gears"></i></th><th></th></tr></thead>';

$dP=$data;$dG=$data;$kNil="";
$dS=$data;$kSesi="";
$dAbs=$dNilai;$kAbs="";
$dDs=$data;$dMhs=$data;

$dL=$dNilai;
$vdMhs="";$vdMhs_="";
foreach($sql as $d){
    $nil=$d['nil'];
    if($d['input']==2){$nil=($d['nil']==0?'Non Aktif':'Aktif');}

    $dt='<tr>
        <td>'.$d['kd'].'</td><td>'.$nil.'</td><td>'.$d['ket'].'</td>
        <td>
            '.Html::a('<i class="fa fa-pencil"></i>',['/pengaturan/update','id'=>$d['id']]).'
            '.Html::a('<i class="fa fa-eye"></i>',['/pengaturan/view','id'=>$d['id']]).'
        </td>
        </tr>';

    $dt1='<tr>
        <td>'.$d['ket'].'</td><td>'.$nil.'</td><td>'.Html::a($d['tot'],['/pengaturan/view','id'=>$d['id']]).'</td>
        <td>'.Html::a('<i class="fa fa-pencil"></i>',['/pengaturan/update','id'=>$d['id']]).'</td>
        </tr>';


    if($d['id']==31){
        $kNil='<h4 id="'.$d['kd'].'">'.$d['ket']." ".Html::a($d['nil']==0?'Not Aktif':'Aktif',['/pengaturan/vd-aktif','id'=>$d['id']],['class'=>$d['nil']==0?'btn btn-danger btn-sm':'btn btn-success btn-sm']).'</h4>';
    }
    if($d['id']==29){
        $kSesi='<h4 id="'.$d['kd'].'">'.$d['ket']." ".Html::a($d['nil']==0?'Not Aktif':'Aktif',['/pengaturan/vd-aktif','id'=>$d['id']],['class'=>$d['nil']==0?'btn btn-danger btn-sm':'btn btn-success btn-sm']).'</h4>';
    }
    if(isset($arrP[$d['id']])||isset($arrG[$d['id']])||isset($arrS[$d['id']])||isset($arrAbs[$d['id']])){
        if(isset($arrP[$d['id']])){$dP.=$dt1;}
        if(isset($arrG[$d['id']])){$dG.=$dt1;}

        if(isset($arrS[$d['id']])){$dS.=$dt1;}
        if(isset($arrAbs[$d['id']])){$dAbs.=$dt;}
        if(isset($arrDs[$d['id']])){$dDs.=$dt1;}
        if(isset($arrMhs[$d['id']])){
            if($d['id']==19){
                $dt1="";
                $vdMhs="Toleransi Berdasarkan ".($d['nil']==0?"Jadwal":"Kehadiran Dosen");
                $vdMhs_=Html::a($d['nil']==0?"Kehadiran Dosen":"Jadwal",['/pengaturan/vd-aktif','id'=>$d['id'],],['class'=>'btn btn-primary btn-sm']);
            }
            $dMhs.=$dt1;
        }

    }else{
        if($d['id']!=31&&$d['id']!=29){
            $dL.=$dt;
        }
    }
}
?>
<style>
    :target {
        color: #00C !important;
        background:#000 !important;
        font-weight:bold;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Pengaturan Nilai</span></div>
    <div class="panel-body">
        <div class="col-sm-12"><?= $kNil ?></div>
        <?php if(Yii::$app->vd->vd()['vdGrade']==1){ ?>
        <div class="col-sm-6"><?= $dP.'</table>' ?></div>
        <div class="col-sm-6"><?= $dG.'</table>' ?></div>
        <?php }else{ ?>
        <div>
           <i>Aktifkan Pengaturan untuk melihat daftar pengaturan</i>
        </div>
        <?php }

        ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Pengaturan Sesi Perkuliahan</span></div>
    <div class="panel-body">
        <div class="col-sm-12"><?= $kSesi ?></div>
        <?php if(Yii::$app->vd->vd()['vdSesi']==1){ ?>
        <div class="col-sm-12"><?= $dS.'</table>' ?></div>
        <?php }else{ ?>
            <div>
                <i>Aktifkan Pengaturan untuk melihat daftar pengaturan</i>
            </div>
        <?php }
        ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Pengaturan Absen Perkuliahan</span></div>
    <div class="panel-body">
        <div class="col-sm-6">Kehadiran Dosen<?= $dDs.'</table>' ?></div>
        <div class="col-sm-6">Kehadiran Mahasiswa<?= $dMhs
            .'<tr  id="vAbsM"><th colspan="2">'.$vdMhs.'</th><th colspan="2">'.$vdMhs_.'</th></tr>'
            .'</table>' ?>
        </div>

    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Pengaturan Lain-Lain</span></div>
    <div class="panel-body">
        <div class="col-sm-12"><?= $dL.'</table>' ?></div>
    </div>
</div>
