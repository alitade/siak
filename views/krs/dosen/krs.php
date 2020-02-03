<?php
use Yii;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use \kartik\widgets\SwitchInput;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;


$this->title = 'Krs '.$model->kr_kode." ( $model->nim)";
$this->params['breadcrumbs'][] = ['label' => 'Perwalian', 'url' => ['dosen']];
$this->params['breadcrumbs'][] = $this->title;
$app=($mHkrs->app?:0);
#echo "KR : ".$mKr->kr_kode;

Modal::begin([
    'header' => '<i class="fa fa-file-o"></i> Nilai Transkrip',
    'id'=>'modals',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id'=>'krs-grid',
    'columns' => [
        [
            'value'=>function($data){return " Semester ".$data["semester"];},
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-grouped-row',
            'groupEvenCssClass'=>'kv-grouped-row',
        ],
        ['header'  => 'Kode','value' => function($data) {return $data[kode_mk];},'format'  => 'raw',],
        ['header'  => 'Matakuliah','value' => function($data) {return $data[nama_mk];},'format'  => 'raw',],
        ['header'  => 'SKS','value' => function($data) {return $data[sks];},'format'  => 'raw',],
        ['header'  => 'Nilai','value' => function($data) {return $data[huruf];},'format'  => 'raw',],
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    #'layout' =>false,
    'panel'=>false,
    'toolbar'=>false
]);
Modal::end();
/*Modal::begin([
    'header' => '<i class="fa fa-file-o"></i> Panduan Approval KRS',
    'id'=>'modals-1',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
]);*/
echo '<!--  div class="box box-info">
<div class="box-body">
'
    .Html::img("@web/panduan/p-ds-01.png",['class'=>"img-responsive img-rounded",'style'=>'border:#000 solid 1px;']).'
    <p>
Untuk menolak / menyetujui matakuliah, klik 
<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-mini bootstrap-switch-id-w2 bootstrap-switch-animate" style="width: 46px;">

    <div class="bootstrap-switch-container" style="width: 66px; margin-left: 0px;">
        <span class="bootstrap-switch-handle-on bootstrap-switch-success" style="width: 22px;"><i class="fa fa-check"></i></span><span class="bootstrap-switch-label" style="width: 22px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-danger" style="width: 22px;"><i class="fa fa-remove"></i></span>
    </div>
 </div>
 
 
     yang ada di kolom “Persetujuan”.
    Kemudian klik tombol Simpan Data Perubahan / Simpan & Kunci KRS Mahasiswa.
    Tombol pertama berfungsi untuk menyimpan status persetujuan tanpa mengunci KRS mahasiswa, sehingga mahasiswa bisa mengambil kembali matakuliah yang ditawarkan.
    Sedangkan tombol ke 2 berfungsi untuk menyimpan perubahan dan mengunci KRS mahasiswa,
    sehingga mahasiswa tidak bisa melakukan penambahan / perubahan terhadap matakuliah yang telah di input.
    </p>
</div>

</div-->';
#echo Html::img("@web/panduan/p-ds-01.png",['class'=>"rounded mx-auto d-block "]);
#echo Html::img("@web/app-krs.png");
//Modal::end();
if($model->tf==1){
?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title" style="font-size:14px;font-family: 'Arial'" ><b> KRS Tahun Akademik <?= $model->kr->kr_kode.' / '.$model->kr->kr_nama ?> <?= ($mKr->kr->kr_nama ? " Semester ".$mKr->kr->kr_nama:"")?></b> </span>
        </div>

        <div class="panel-body">
            <div class="row" style="font-weight: bold;font-size: 14px">
                <div class="col-sm-6"> <?= $model->mhs->dft->bio->Nama." ($model->nim)"?></div>
                <div class="col-sm-6"><span class="pull-right">Maksimal SKS: <?= $maxSks['nil']?:0 ?> sks</span> </div>
            </div>
            <?php
            if($model->app){
                $appTgl=explode(' ',$model->app_date);
                echo"
                <p></p>
                <div class='col-sm-12 text-center bg-info' style='border:solid 1px #000;font-weight: bold;font-size: 16px'>
                KRS telah terkunci pada tanggal ".\app\models\Funct::TANGGAL($appTgl[0]).', '.substr($appTgl[1],0,5)
                    ." penambahan / perubahan data krs tidak bisa dilakukan
                </div><div class='clearfix'></div>";
            }
            echo"
                <p></p>
                <div class='col-sm-12 text-center bg-info' style='border:solid 1px #000;font-weight: bold;font-size: 16px'>
                Data KRS ini telah diproses ke data perkuliahan, proses pembatalan perkuliahan tidak bisa dilakukan
                </div><div class='clearfix'></div>";
            ?>

            <p></p>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th width="1%"> No </th>
                    <th width="1%"> <i class="fa fa-info-circle"></i></th>
                    <th width="1%"> Kode</th>
                    <th> Matakuliah </th>
                    <th width="1%"> SKS </th>
                    <th width="1%"> Kls. </th>
                    <th> Jadwal </th>
                    <th width="1px"> Status </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n=0;$totSks=0;$status=[0=>'Ditunda','Disetujui','Ditolak'];
                foreach ($listKrs as $d){
                    $n++;
                    if($d['krs_stat']!=2){$totSks+=$d['mtk_sks'];}

                    $info=$d['krs_ulang']==1?"[U]":"[B]";
                    $jdwl=explode("|",$d['subjadwal']);
                    $jd = "";
                    foreach($jdwl as $k=>$v){
                        $Info=explode('#',$v);
                        $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";
                        $jd .=$ket."<br />";
                    }

                    $stat=0;

                    if($d['ig']==1){$jd='-';}
                    $rowspan="";
                    if($d['ket']!=''){$rowspan="rowspan='2'";}
                    $mk="<span class='badge' style='background:".($d['mkkr']==1?'green':'red').";'>$d[mtk_kode]</span> ";

                    $class='';$stat=$status[$d['krs_stat']];
                    $stat="<span class='label label-default' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    if($d['krs_stat']=='1'){
                        $class='class="bg-success"';
                        $stat="<span class='label label-success' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    }
                    if($d['krs_stat']=='2'){
                        $class='class="bg-danger"';
                        $stat="<span class='label label-danger' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    }

                    echo"<tr $class ".($d['krs_stat']==2?"style='text-decoration: line-through'":"" ).">
                        <td $rowspan >$n</td>
                        <th>$info</th>
                        <td>$mk</td>
                        <td>$d[mtk_nama]<br><i class='fa fa-user'></i> $d[ds_nm]</td>
                        <td class='text-right'>$d[mtk_sks]</td>
                        <td class='text-right'>$d[jdwl_kls]</td>
                        <td>$jd</td>
                        <td>$stat</td>
                    </tr>";
                    if($d['ket']!=''){echo "<tr><td colspan='6'> $d[ket]</td></tr>";}
                }
                ?>
                </tbody>
                <?="
                <tfoot>
                    <tr><th colspan='4' class='text-right'>TOTAL</th><th class='text-right'>$totSks</th><th colspan='3'></th></tr>
                </tfoot>" ?>
            </table>
            <p></p>
            <?php if(count($model->krsdet)==0){ ?>
                <div class="alert alert-info alert-md text-center" style="font-size: 18px;font-weight:bold"> Data KRS Tidak Ditemukan</div>
            <?php } else {
                ?>

            <?php
            }
            ?>

        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-10 col-md-10"><?= $LOG ?></div>
                <div class="col-sm-2 col-md-2 pull-right"><?= Html::a('<i class="fa fa-th-list"></i> Daftar Riwayat Akses',['/biodata/log-list','id'=>$model->id],['target'=>'_blank'])?></div>
            </div>
        </div>

    </div>

<?php } else{ ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title" style="font-size:14px;font-family: 'Arial'" ><b> KRS Tahun Akademik <?= $model->kr->kr_kode.' / '.$model->kr->kr_nama ?> <?= ($mKr->kr->kr_nama ? " Semester ".$mKr->kr->kr_nama:"")?></b> </span>
        </div>

        <div class="panel-body">
            <div class="row" style="font-weight: bold;font-size: 14px">
                <div class="col-sm-6"> <?= $model->mhs->dft->bio->Nama." ($model->nim)"?></div>
                <div class="col-sm-6"><span class="pull-right">
                        <?php
                        #echo $mMhs->mhs_nim;
                        $ipk=Yii::$app->db2->createCommand("select dbo.ipk('".$model->mhs->mhs_nim."') ipk")->queryOne();
                        echo " IPK : ".number_format($ipk['ipk'],2);
                        #\app\models\Funct::v(YIi::$app->db2)
                        ?>


                    </span> </div>
            </div>
            <?php
            if($model->app){
                $appTgl=explode(' ',$model->app_date);
                echo"
                <p></p>
                <div class='col-sm-12 text-center bg-info' style='border:solid 1px #000;font-weight: bold;font-size: 16px'>
                KRS telah terkunci pada tanggal ".\app\models\Funct::TANGGAL($appTgl[0]).', '.substr($appTgl[1],0,5)
                    ." penambahan / perubahan data krs tidak bisa dilakukan
                </div><div class='clearfix'></div>";
            }
            ?>

            <p></p>
            <?php
            $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_VERTICAL,
                'id'=>'create-krs',
                'action' => Url::to(['krs/ds-app']),
                'method'=>'post'
            ]);
            echo Html::hiddenInput("head",$model->id,["readOnly"=>"true"]);

            ?>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th width="1%"> No </th>
                    <th width="1%"> <i class="fa fa-info-circle"></i></th>
                    <th width="1%"> Kode</th>
                    <th> Matakuliah </th>
                    <th width="1%"> SKS </th>
                    <th width="1%"> Kls. </th>
                    <th> Jadwal </th>
                    <th width="1px"> Status </th>
                    <th width="1%"> Persetujan </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n=0;$totSks=0;$status=[0=>'Ditunda','Disetujui','Ditolak'];
                foreach ($listKrs as $d){
                    $n++;
                    if($d['krs_stat']!=2){$totSks+=$d['mtk_sks'];}

                    $info=$d['krs_ulang']==1?"[U]":"[B]";
                    $jdwl=explode("|",$d['subjadwal']);
                    $jd = "";
                    foreach($jdwl as $k=>$v){
                        $Info=explode('#',$v);
                        $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";
                        $jd .=$ket."<br />";
                    }

                    $stat=0;

                    if($d['ig']==1){$jd='-';}
                    $rowspan="";
                    if($d['ket']!=''){$rowspan="rowspan='2'";}

                    #if($d['krs_stat']==2){$jd="<s>$jd</s>";}


                    $mk="<span class='badge' style='background:".($d['mkkr']==1?'green':'red').";'>$d[mtk_kode]</span> ";
                    #return $mk;

                    #$rd=Html::radioList("app[".$d[jdwl_id]."]",($d['krs_stat']=='2'?'2':'1'),[2=>'Ditolak',1=>'Disetujui']);
                    $arrSwitch=[
                        'name' => "app[".$d[jdwl_id]."]",
                        'value'=> 1,
                        'pluginOptions' => [
                            'size' => 'mini',
                            'onColor' => 'success',
                            'offColor' => 'danger',
                            'onText' => '<i class="fa fa-check"></i>',
                            'offText' => '<i class="fa fa-remove"></i>',
                            #'state'=>$d['krs_stat']==2?false:true
                        ],
                    ];

                    if($d['krs_stat']==2){
                        $arrSwitch=[
                            'name' => "app[".$d[jdwl_id]."]",
                            'pluginOptions' => [
                                'size' => 'mini',
                                'onColor' => 'success',
                                'offColor' => 'danger',
                                'onText' => '<i class="fa fa-check"></i>',
                                'offText' => '<i class="fa fa-remove"></i>',
                            ],
                        ];
                    }

                    $rd =SwitchInput::widget(
                        $arrSwitch/*
                             [
                                'name' => "app[".$d[jdwl_id]."]",
                                'value'=>1,
                                'pluginOptions' => [
                                    'size' => 'mini',
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                    'onText' => '<i class="fa fa-check"></i>',
                                    'offText' => '<i class="fa fa-remove"></i>',
                                    #'state'=> $d['krs_stat']==2?false:true,

                                ],
                            ]
                             #*/

                    );

                    if($model->app==1){$rd="";}
                    $hide=Html::hiddenInput("id[]",$d['id'],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"]);
                    $class='';$stat=$status[$d['krs_stat']];
                    $stat="<span class='label label-default' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    if($d['krs_stat']=='1'){
                        $class='class="bg-success"';
                        $stat="<span class='label label-success' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    }
                    if($d['krs_stat']=='2'){
                        $class='class="bg-danger"';
                        $stat="<span class='label label-danger' style='font-size: 12px'>".$status[$d['krs_stat']]."</span>";
                    }

                    echo"<tr $class ".($d['krs_stat']==2?"style='text-decoration: line-through'":"" ).">
                        <td $rowspan >$n</td>
                        <th>$info</th>
                        <td>$mk</td>
                        <td>$d[mtk_nama]<br><i class='fa fa-user'></i> $d[ds_nm]</td>
                        <td class='text-right'>$d[mtk_sks]</td>
                        <td class='text-right'>$d[jdwl_kls]</td>
                        <td>$jd</td>
                        <td>$stat</td>
                        <td>$rd $hide</td>
                    </tr>";
                    if($d['ket']!=''){echo "<tr><td colspan='6'> $d[ket]</td></tr>";}
                }
                ?>
                </tbody>
                <?="
                <tfoot>
                    <tr><th colspan='4' class='text-right'>TOTAL</th><th class='text-right'>$totSks</th><th colspan='3'></th></tr>
                </tfoot>" ?>
            </table>
            <?php if(count($model->krsdet)==0){ ?>
                <div class="alert alert-info alert-md text-center" style="font-size: 18px;font-weight:bold"> Mahasiswa Belum Memilih Jadwal Perkuliahan</div>
            <?php } else {

                echo'<div class="text-left" style="font-weight: bold">*Proses Approval selesai jika dosen wali sudah mengunci KRS mahasiswa </div>';


                if ($model->app == '1') {
                    echo Html::a('<i class="fa fa-unlock"></i> Buka Kunci KRS Mahasiswa', ['krs/ds-unapp', 'id' => $model->id], ['class' => 'btn btn-danger']);
                } else {
                    echo
                        Html::submitButton('1) <i class="fa fa-save"></i> Simpan Data Perubahan', ['class' => 'btn btn-primary', 'name' => 's']).' '
                        .Html::submitButton('2) <i class="fa fa-lock"></i> Simpan & Kunci KRS Mahasiswa', ['class' => 'btn btn-danger', 'name' => 'sk']);
                }
                echo '<div class="pull-right">'.
                    #Html::a('<i class="fa fa-eye"></i> Panduan',['#'],['class'=>'btn btn-info','id'=>'popupModal-1']).' '
                    Html::a('<i class="fa fa-eye"></i> Detail Nilai',['#'],['class'=>'btn btn-info','id'=>'popupModal']).'</div>';
                ActiveForm::end();
                ?>


                <p></p>
                <div style="border: solid 1px #000;padding: 4px">
                    <table class="table table-bordered table-condensed" style="text-transform:capitalize">
                        <tr>
                            <th>
                                1) Mahasiswa bisa melakukan penambahan jadwal, Proses APPROVE belum Dilakukan<br>
                                2) Mahasiswa Tidak bisa melakukan penambahan jadwal, Proses APPROVE Selesai Dilakukan<br>
                            </th>
                        </tr>

                        <tr>
                            <th><span class="badge" style="background:green">Kurikulum Matakuliah Sesuai </span>
                                <span class="badge" style="background:red">Kurikulum Matakuliah Tidak Sesuai </span>
                            </th>
                        </tr>
                        <tr>
                            <th><i class="fa fa-info-circle"></i> <i class="fa fa-arrow-right"></i>[B] : Matakuliah Baru |
                                [U] : Matakuliah Mengulang
                            </th>
                        </tr>
                        <tr>
                            <th>
                                - Mahasiswa tidak bisa menghapus data jadwal yang telah diterima / ditolak oleh dosen
                                wali<br>
                                - Mahasiswa tidak bisa menambah data jadwal jika krs sudah dikunci oleh dosen wali
                            </th>
                        </tr>
                    </table>

                </div>
                <?php
            }
            ?>

        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-10 col-md-10"><?= $LOG ?></div>
                <!--div class="col-sm-2 col-md-2 pull-right"><?= Html::a('<i class="fa fa-th-list"></i> Daftar Riwayat Akses',['/biodata/log-list','id'=>$model->id],['target'=>'_blank'])?></div-->
            </div>
        </div>

    </div>

<?php } ?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");


$this->registerJs("$(function() {
   $('#popupModal-1').click(function(e) {
     e.preventDefault();
     $('#modals-1').modal('show').find('.modal-content').html(data);
   });
});");








