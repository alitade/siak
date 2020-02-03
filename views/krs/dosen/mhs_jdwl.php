<?php
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\Kalender;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use DateTime;
use yii\bootstrap\Modal;
#Funct::v($JD);

$this->title = 'Jadwal Perkuliahan '.$model->kr_kode." ( $model->mhs_nim)";
$this->params['breadcrumbs'][] = ['label' => 'Perwalian', 'url' => ['dosen']];
$this->params['breadcrumbs'][] = $this->title;

#Funct::v($model);

Modal::begin([
    'header' => '<i class="fa fa-indfo"></i> INFORMASI',
    'id'=>'modals',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    #'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    #'toggleButton' => ['label' => 'Keterangan','options'=>['class'=>'btn btn-success']],
]);
?>
<table class="table table-bordered" style="text-transform:capitalize">
    <tr><th><span class="badge" style="background:green">Kurikulum Matakuliah Sesuai </span> <span class="badge" style="background:red">Kurikulum Matakuliah Tidak Sesuai </span></th></tr>
    <tr><th>[B] : Matakuliah Belum Diambil </th></tr>
    <tr><th>[S] : Matakuliah Sudah Diambil & Nilai Sudah Terdaftar di Transkrip </th></tr>
    <tr><th>[U] : Matakuliah Sudah Diambil & Nilai Tidak Terdaftar di Transkrip Karena tidak memenuhi persyaratan komponen nilai</th></tr>
    <tr class="danger">
        <th>Background baris akan berwana merah jika:
            <ul>
                <li>terdapat jadwal yang bersisipan</li>
                <li>terdapat kode matakuliah yang telah dipilih</li>
                <?=(Yii::$app->vd->vd()['qKRS']==1?"<li>Kapasitas Ruangan penuh</li>":"") ?>
            </ul>
        </th>
    </tr>
    <tr class="success"><th>Background baris akan berwana hijau jika jadwal telah tersimpan didalam sistem </th></tr>
</table>

<?php
Modal::end();
#echo Html::a("<i class='fa fa-info-circle'></i> Klik untuk melihat informasi",["#"],['class'=>'btn btn-primary btn-sm','id'=>'popupModal'])." ";
?>

<?php
Modal::begin(['header' =>false,'id' => 'ModMk',]);
echo $dataMatkul;
Modal::end();

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title" style="font-size:14px;font-family: 'Arial'" ><i class="fa fa-th-list"></i> Jadwal Semester <?= $mKr->kr->kr_nama ?></span>
    </div>

    <div class="panel-body">
        <div class="row" style="font-weight: bold;font-size:14px;">
            <div class="col-sm-6"> <?= $model->dft->bio->Nama." ($model->mhs_nim)"?></div>
            <div class="col-sm-6"><span class="pull-right"><?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama.' ('.$model->pr->pr_nama.')' ?> </span> </div>
        </div>
        <p></p>
        <?=
        GridView::widget([
            'dataProvider' => $data,
            'rowOptions'=>function($data) use($JD){
                $dis = false;
                $jdwl=explode("|",$data['subJadwal']);$jd="";
                $sisa = $data['kapasitas']-$data['mhs'];
                if($data['ig']==0){
                    foreach($jdwl as $k=>$v){
                        $Info=explode('#',$v);
                        $ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";
                        if(isset($JD['JD'][$Info[1]])){
                            foreach($JD['JD'][$Info[1]] as $d){
                                $M = date('H:i',strtotime($d['m']));$K=date('H:i',strtotime($d['k']));
                                $m = date('H:i',strtotime($Info[2]));
                                $k = date('H:i',strtotime($Info[3]));
                                #Perbandingan KRS Dengan Jadwal
                                if($m >= $M && $m <$K){$dis=true;}if($k > $M && $k <$K){$dis=true;}
                                #Perbandingan KRS Dengan Jadwal
                                if($M >= $m && $M <$k){$dis=true;}if($K > $m && $K <$k){$dis=true;}
                            }
                        }
                    }
                    if($data['kapasitas']>0 && $sisa<=0){$dis=true;}

                }

                if(isset($JD['MK'][$data['mtk_kode']])){$dis=true;}
                if(isset($JD['ID'][$data['jdwl_id']])){
                    if($JD['ID'][$data['jdwl_id']]==2){return ['class' => 'success','style'=>'font-weight:bold;text-decoration: line-through;'];}
                    return ['class' => 'success','style'=>'font-weight:bold'];
                }else{if($dis){return ['class' => 'danger','style'=>'font-weight:bold'];}}
            },
            'id'=>'krs-grid',
            #'filterModel' => '',
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'value'=>function($data){
                        return " Semester ".$data["mtk_semester"];
                    },
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'header'  => 'Kode',
                    'value' => function($data) {
                        $mk="<span class='badge' style='background:".($data['KR']==1?'green':'red').";'>$data[mtk_kode]</span> ";
                        return $mk;

                    },
                    #'visible'=>$kuota==0?false:true,
                    'format'  => 'raw',
                ],
                [
                    'header'=>'<i class="fa fa-info"></i>',
                    'format'=>'raw',
                    'value'=>function($data){return  "<span><b>[$data[Status]]</b></span> ";}

                ],
                [
                    'header'  => 'Matakuliah',
                    'value' => function($data) {
                        return $data["mtk_nama"]."<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user'></i> $data[ds_nm]";
                    },
                    'format'  => 'raw',
                ],
                [
                    'header'  => 'Jadwal | Ruang',
                    'value' => function($data) {
                        $jdwl=explode("|",$data['subJadwal']);
                        $jd = "";
                        foreach($jdwl as $k=>$v){
                            $Info=explode('#',$v);
                            $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
                            $jd .=$ket."<br />";
                        }
                        return $jd;
                    },
                    'format'  => 'raw',
                ],
                [
                    'header'  => 'Kls',
                    'value' => function($data) {
                        return $data["jdwl_kls"];
                    },
                    'format'  => 'raw',
                ],
                [
                    'header'  => 'SKS',
                    'value' => function($data) {
                        return $data["mtk_sks"];
                    },
                    'format'  => 'raw',
                ],
                [
                    'header'  => '<i class="fa fa-home"></i>',
                    'value' => function($data) {
                        return $data['kapasitas'];
                    },
                    'format'  => 'raw',
                ],
                [
                    'header'  => '<i class="fa fa-users"></i>',
                    'value' => function($data) {
                        return $data['mhs'];
                    },
                    'format'  => 'raw',
                ],
                [
                    'value' => function ($data)use($JD) {
                        if(isset($JD['ID'][$data['jdwl_id']])){
                            return'<i class="fa fa-check"></i>';
                        }return false;
                    },
                    'format'  => 'raw',
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'bordered'=>true,
            'layout' =>false,
            'panel'=>[
                'type'=>GridView::TYPE_DEFAULT,
                'heading'=>false
                #'<span style="text-transform:uppercase;font-size:14px;font-weight:bold;font-family: Arial"><i class="fa fa-th-list"></i> JADWAL PIlIHAN '.$model->mhs_nim.' SEMESTER '.$mKr->kr->kr_nama." (MAKS. $maxSks[nil] SKS)".'</span>'
                ,
                'before'=>'<i class="fa fa-info"></i> : Info Matkul | <i class="fa fa-home"></i> : Kuota Jadwal | <i class="fa fa-users"></i> : Jumlah Mahasiswa'
                    .'<div class="pull-right"> '.Html::a("<i class='fa fa-info-circle'></i> Klik untuk melihat informasi",["#"],['class'=>'btn btn-primary btn-sm','id'=>'popupModal']).'</div>'
                ,
                'footer'=>false,
                'after'=>false,
            ],
            'toolbar'=>false
        ]);
        ?>


    </div>

</div>

<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

$this->registerJs("$(function() {
   $('#_ModMk').click(function(e) {
     e.preventDefault();
     $('#ModMk').modal('show').find('.modal-content').html(data);
   });
});");

?>

