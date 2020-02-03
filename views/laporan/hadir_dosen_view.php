<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\LAbsenDosenSearch $searchModel
 */



$bln=array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");

$datetime1 = date_create($model->tgl_awal);
$datetime2 = date_create($model->tgl_akhir);
$interval = date_diff($datetime1, $datetime2);
$jmlTgl   = $interval->format('%a')."<br>";
$tdTgl=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];
$sTgl="<th>$tdTgl</th>";
while($datetime1!=$datetime2){
    date_add($datetime1, date_interval_create_from_date_string('1 days'));
    $tdTgl=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];
    $sTgl.="<th>$tdTgl</th>";
    #echo date_format($datetime1, 'Y-m-d')."<br>";
}
#die();
$kls=[0=>'Pagi','Sore'];
$shetName = "Kelas ".$kls[$model->tipe].' Periode '.Funct::TANGGAL($model->tgl_awal).'-'.Funct::TANGGAL($model->tgl_akhir);
$fileName="Kehadiran Dosen - ".$model->kr_kode." - ".$shetName;


$this->title="Detail Kehadiran Dosen";
$this->params['breadcrumbs'][] = ['label' => 'Kehadiran Dosen', 'url' => ['kehadiran-dosen']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"><?= $fileName  ?> </span>
    </div>
    <div class="panel-body">
        <?= Html::a('<i class="fa fa-download"></i> Download Data',['/laporan/download-persensi-dosen','id'=>$model->id],['class'=>'btn btn-primary','target'=>'_blank']) ?><p></p>
        <div class="raw">
            <style>

                #c {
                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }


                #c td, #c th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }

                #c tr:nth-child(even){background-color: #f2f2f2;}

                #c tr:hover {background-color: #ddd;}

                #c th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #4CAF50;
                    color: white;
                }

                /*
                #c {
                    border-collapse: collapse;
                }
                #c td,
                #c th div {
                    border: 1px solid black;
                    width: 100%;
                }
                #c td,
                #c th {
                    width: 250px;
                    min-width: 250px;
                    padding: 0;
                }
                #block {
                    width: 100%;
                }

                */
            </style>
            <div style="overflow:auto;height:1000px" >
                <table style=" white-space: nowrap;" id="c" >
                    <thead>
                    <tr>
                        <th>NO.</th>
                        <!-- 1 --> <th>TIPE DOSEN</th>
                        <!-- 2 --><th>MAKS. SKS.</th>
                        <!-- 3 --><th>DOSEN</th>
                        <!-- 4 --><th>PELAKSANA</th>
                        <!-- 5 --><th>PERGANTIAN</th>
                        <!-- 6 --><th>MTK.</th>
                        <!-- 7 --><th>SKS</th>
                        <!-- 8 --><th>JADWAL</th>
                        <!-- 9 --><th>KLS.</th>
                        <!-- 10--><th>JML. MHS.</th>
                        <?= $sTgl ?>
                        <th>HADIR</th>
                    </tr>

                    </thead>
                    <tbody>
                    <?php
                    $GKode = $def['GKode'];
                    #$GKode='';
                    $n=0;
                    $KLS="";$pKLS="";
                    $MK="";$pMK="";
                    $td="";$totMhs=0;
                    $SKS=0;
                    foreach($q as $d){
                        if($GKode!=$d[0]){
                            echo $td;
                            $GKode=$d[0];$pKLS="";$KLS="";$n++;
                            $MK="";$pMK="";$totMhs=0;
                            $SKS=0;
                        }
                        if($SKS<$d[7]){$SKS=$d[7];}

                        if($pKLS!=$d[9]){$pKLS=$d[9];$KLS.=",$pKLS";}
                        if($pMK!=$d[6]){$pMK=$d[6];$MK[$pMK]=$d[6];}
                        $totMhs+=$d[10];

                        #$KLS.=",$d[9]";
                        $td ="<tr><td>$n</td><td> $d[1]</td><td> $d[2] </td><td> $d[3] </td><td> $d[4] </td><td> $d[5] </td>
                            <td>  ".implode("<br>",$MK)." </td>
                            <td> $SKS </td><td> $d[8] </td><td> ".substr($KLS,1)."  </td><td> $totMhs </td>";
                        $hadir=0;
                        for($i=11;$i<=($jmlTgl+11);$i++){
                            $hadir+=$d[$i];
                            $td.="<td>$d[$i]</td>";
                        }
                        $td.="<td>$hadir</td></tr>";
                    }
                    echo $td;
                    ?>
                    </tbody>
                </table>
            </div>
            <div class=""></div>
        </div>

    </div>

</div>

