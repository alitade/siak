<?php
use Yii;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use \kartik\widgets\SwitchInput;
use yii\helpers\Url;


$this->title = 'Krs '.$model->kr_kode." ( $model->nim)";
$this->params['breadcrumbs'][] = ['label' => 'Perwalian', 'url' => ['dosen']];
$this->params['breadcrumbs'][] = $this->title;
$app=($mHkrs->app?:0);
#echo "KR : ".$mKr->kr_kode;
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
        <p></p>
        <?php if(count($model->krsdet)==0){ ?>
            <div class="alert alert-info alert-md text-center" style="font-size: 18px;font-weight:bold"> Mahasiswa Belum Memilih Jadwal Perkuliahan</div>
        <?php } else {


            if ($model->app == '1') {
                echo Html::a('<i class="fa fa-unlock"></i> Buka Kunci KRS Mahasiswa', ['krs/ds-unapp', 'id' => $model->id], ['class' => 'btn btn-success']);
            } else {
                echo Html::submitButton('<i class="fa fa-save"></i> Simpan Data Perubahan', ['class' => 'btn btn-primary', 'name' => 's'])
                    . " " . Html::submitButton('<i class="fa fa-lock"></i> Simpan & Kunci KRS Mahasiswa', ['class' => 'btn btn-primary', 'name' => 'sk']);
            }
            ActiveForm::end();
            ?>


            <p></p>
            <div style="border: solid 1px #000;padding: 4px">
                <table class="table table-bordered table-condensed" style="text-transform:capitalize">
                    <tr>
                        <th><span class="badge" style="background:green">Kurikulum Matakuliah Sesuai </span> <span
                                    class="badge" style="background:red">Kurikulum Matakuliah Tidak Sesuai </span></th>
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
            <div class="col-sm-2 col-md-2 pull-right"><?= Html::a('<i class="fa fa-th-list"></i> Daftar Riwayat Akses',['/biodata/log-list','id'=>$model->id],['target'=>'_blank'])?></div>
        </div>
    </div>

</div>


