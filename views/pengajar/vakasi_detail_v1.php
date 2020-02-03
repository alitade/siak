<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;
use app\models\Funct;


$this->title = 'Detail Vakasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
    <h4 class="panel-title"><?= $this->title?> <?= $ModBn->kln->kr_kode ?> | <?= $ModBn->ds->ds_nm ?></h4>
    </div>
    <div class="panel-body">
    <?php
        $GKODE="";
        $TABLE='<table class="table table-bordered">';
        $TABLE1="";#'<table class="table table-bordered">';
        $TABLE='';
    $totMhs=0;$totT1=0;$totUts=0;$totT2=0;$totUas=0;
    $byT1=0;$byUts=0;$byT2=0;$byUas=0;
        if($qVakasi){
            $n=0;$n1=0;
            foreach ($qVakasi as $data){
               $n++;
               if($data['GKode']!=$GKODE){
                   if($n>1){$TABLE1.="
                        <tr>
                            <td rowspan='3' colspan='2'> BTN </td>    
                            <th style='text-align: right'> Total</th>   
                            <td> $totMhs </td>   
                            <td> $totT1 </td>   
                            <td> $totUts </td>   
                            <td> $totT2 </td>   
                            <td> $totUas </td>   
                            <td> </td>   
                        </tr> 
                        <tr>
                            <th style='text-align: right' colspan='2'> Terbayar </th>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                        </tr> 
                        <tr>
                            <th style='text-align: right' colspan='2'> Sisa </th>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                        </tr> 
                       </table>
                    ";
                   }
                   $n1++;
                   $GKODE=$data['GKode'];
                    $jam=Funct::HARI()[$data['jdwl_hari']].", $data[jdwl_masuk] - $data[jdwl_keluar]";
                    #$TABLE.='<thead><tr><th>'.$data['GKode']." ($jam) ".'</th></tr></thead>';
                    $TABLE.='<h5><b>'.$data['GKode']." ($jam) ".'</b></h5>';
                    $TABLE.='
                    <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>'.$n.'</th>
                        <th>Jurusan</th>
                        <th>Matakuliah</th>
						<th>&sum;Mhs</th>
						<th>&sum;Tgs1</th>
						<th>&sum;UTS</th>
						<th>&sum;Tgs2</th>
						<th>&sum;UAS</th>
						<th> </th>
                    </tr>
                    </thead>
                   </table>';
                   $TABLE1.='<h5><b>'.$data['GKode']." ($jam) ".'</b></h5>
                    <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>'.$n1.'</th>
                        <th>Jurusan(Kelas)</th><th>Matakuliah (SKS)</th>
						<th>&sum;Mhs</th><th>&sum;Tgs1</th><th>&sum;UTS</th><th>&sum;Tgs2</th><th>&sum;UAS</th><th> </th>
                    </tr>
                    </thead>';
                   $totMhs=0;$totT1=0;$totUts=0;$totT2=0;$totUas=0;
                   $byT1=0;$byUts=0;$byT2=0;$byUas=0;

               }
                $totMhs+=$data['tot'];$totT1+=$data['tgs1'];$totUts+=$data['uts'];$totT2+=$data['tgs2'];$totUas+=$data['uas'];
                $byT1+=$data['t1'];$byUts+=$data['ut'];$byT2+=$data['t2'];$byUas+=$data['ua'];

               $TABLE1.='
                <tr>
                    <td colspan="2">'."$data[jr_jenjang] $data[jr_nama] ($data[pr_nama] | $data[jdwl_kls])".'</td>
                    <td>'."$data[mtk_kode] $data[mtk_nama] ($data[mtk_sks])".'</td>
                    <td>'."$data[tot]".'</td><td>'."$data[tgs1]".'</td><td>'."$data[uts]".'</td><td>'."$data[tgs2]".'</td><td>'."$data[uas]".'</td>
                    <td></td>
                </tr>';


           }
            echo $TABLE1."
                        <tr>
                            <td rowspan='3' colspan='2'> BTN </td>    
                            <td> Total</td><td> $totMhs </td><td> $totT1 </td><td> $totUts </td><td> $totT2 </td><td> $totUas </td>   
                            <td> </td>   
                        </tr> 
                        <tr>
                            <td> Terbayar </td>   
                            <td> $byT1 </td><td> $byUts </td><td> $byT2 </td><td> $byUas </td>
                            <td> </td>   
                        </tr> 
                        <tr>
                            <td> Sisa </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                            <td> </td>   
                        </tr> 
                       </table>";

            #echo $TABLE.'';



        }

    ?>



    </div>
</div>

