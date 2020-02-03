<?php
use Yii;
use yii\helpers\Html;

$this->title = 'KRS';
#$this->params['breadcrumbs'][] = ['label' => 'KRS', 'url' => ['krs/mhs']];
$this->params['breadcrumbs'][] = $this->title;
$app=($mHkrs->app?:0);
#echo "KR : ".$mKr->kr_kode;


?>


<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title" ><b> INPUT KRS <?= ($mKr->kr->kr_nama ? " Semester ".$mKr->kr->kr_nama:"")?></b> </span>
    </div>

    <div class="panel-body">
        <?php
        if(!$mKr->kr_kode){
//        if(false){
            ?>
            <div class="alert alert-default text-center"><span style="font-size: 18px;font-weight: bold;color: #000"> Jadwal Perwalian Belum Tersedia</span></div>
        <?php
        }else{if($mReg->id){
            ?>
            <div style="border: solid 1px #000;padding: 4px">
                <table class="table table-bordered table-condensed" style="text-transform:capitalize">
                    <tr><th><span class="badge" style="background:green">Kurikulum Matakuliah Sesuai </span> <span class="badge" style="background:red">Kurikulum Matakuliah Tidak Sesuai </span></th></tr>
                    <tr><th><i class="fa fa-info-circle"></i> <i class="fa fa-arrow-right"></i>[B] : Matakuliah Baru | [U] : Matakuliah Mengulang </th></tr>
                    <tr><th>
                            <span style="color: red;">- Jadwal perkuliahan yang tidak disetujui dosen wali, tidak akan terdaftar dalam jadwal perkuliahan mahasiswa</span><br>
                            - Jika status jadwal masih "ditunda" silahkan menghubungi dosen wali masing-masing<br>
                            - Mahasiswa tidak bisa menghapus data jadwal yang telah diterima / ditolak oleh dosen wali<br>
                            - Mahasiswa tidak bisa menambah data jadwal jika krs sudah dikunci oleh dosen wali
                        </th></tr>
                </table>

            </div>
            <p></p>

            <?php
            if($app!=1 && $open){echo Html::a('<i class="fa fa-th-list"></i> Input Jadwal',['/krs/mhs-jadwal'],['class'=>'btn btn-primary'])." ";}

            if($mHkrs->id!=null){echo Html::a('<i class="fa fa-print"></i> Cetak KRS',['krs/mhs-cetak','id'=>$mHkrs->id],['class'=>'btn btn-primary','target'=>'_blank']);}

            if(!$open){
                echo"
                <p></p>
                <div class='col-sm-12 text-center bg-danger' style='font-weight: bold;font-size: 16px;padding: 5px;'>Input KRS Online Telah Berakhir!!!  
                </div><div class='clearfix'></div>";
            }

            if($mHkrs->app==1){
                $appTgl=explode(' ',$mHkrs->app_date);
                echo"
                <p></p>
                <div class='col-sm-12 text-center bg-info' style='border:solid 1px #000;font-weight: bold;font-size: 16px'>
                KRS telah terkunci pada tanggal ".\app\models\Funct::TANGGAL($appTgl[0]).', '.substr($appTgl[1],0,5)
                ." penambahan / perubahan data krs tidak bisa dilakukan
                </div><div class='clearfix'></div>";
            }

            ?>
            <p></p>
            <div class="row" style="font-weight: bold;font-size: 14px">
                <div class="col-sm-6"> Dosen Wali: <?= $mMhs->wali->ds_nm ?></div>
                <div class="col-sm-6"><span class="pull-right">
                        <?php
                        #echo $mMhs->mhs_nim;
                        $ipk=YIi::$app->db2->createCommand("select dbo.ipk('$mMhs->mhs_nim') ipk")->queryOne();
                        echo " IPK : ".number_format($ipk['ipk'],2);
                        #\app\models\Funct::v(YIi::$app->db2)
                        ?>
            </div>
            <p></p>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th width="1%"> No </th>
                    <th width="1%"> <i class="fa fa-info-circle"></i></th>
                    <th> Kode</th>
                    <th> Matakuliah </th>
                    <th width="1%"> SKS </th>
                    <th width="1%"> Kls. </th>
                    <th> Jadwal </th>
                    <th> Status </th>
                    <th> </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n=0;$totSks=0;$status=[0=>'Ditunda','Disetujui','Ditolak'];
                foreach ($listKrs as $d){
                    $n++;
                    if($d['krs_stat']!=2){$totSks+=$d['mtk_sks'];}

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
                    $lDel="";
                    if( $d['krs_stat']==0 && $app!=1 && $open){$lDel=Html::a('<i class="fa fa-trash"></i>',['krs/mhs-del','id'=>$d['id']],['data-method'=>'post']);}
                    $info=$d['krs_ulang']==1?"[U]":"[B]";
                    $mk="<span class='badge' style='background:".($d['mkkr']==1?'green':'red').";'>$d[mtk_kode]</span> ";
                    $matkul=$d["mtk_nama"];
                    if(Yii::$app->vd->vid(66,1,true)){
                        $matkul.="<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user'></i> $d[ds_nm]";
                    }


                    echo"<tr ".($d['krs_stat']==2?"style='text-decoration: line-through'":"" ).">
                        <td $rowspan >$n</td>
                        <th>$info</th>
                        <td>$mk</td>
                        <td>$matkul</td>
                        <td class='text-right'>$d[mtk_sks]</td>
                        <td class='text-right'>$d[jdwl_kls]</td>
                        <td>$jd</td>
                        <td>".$status[$d['krs_stat']]."</td>
                        <td>$lDel</td>
                    </tr>";
                    if($d['ket']!=''){echo "<tr><td colspan='6'> $d[ket]</td></tr>";}
                }
                ?>
                </tbody>
                <?="
                <tfoot>
                    <tr>
                        <th colspan='4' class='text-right'>TOTAL</th>
                        <th class='text-right'>$totSks</th>
                        <th colspan='3'></th>
                    </tr>
                </tfoot>" ?>
            </table>
            <p></p>


            <?php
            }else{
            echo'
            <div class="alert alert-default text-center">
                <span style="font-size: 18px;font-weight: bold;color: #000">
                    Data anda tidak ditemukan dalam daftar registrasi perwalian '.($mKr->kr->kr_nama ? " Semester ".$mKr->kr->kr_nama:"").'<br>
                    Silahkan hubungi bagian keuangan untuk melakukan registrasi perwalian
                </span>
            </div>';
            }

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


