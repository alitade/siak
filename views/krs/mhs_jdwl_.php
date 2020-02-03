<?php
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\Kalender;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;


?>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title">
            <i class="fa fa-th-list"></i>
            JADWAL PERKULIAHAN
        </span>
    </div>

    <div class="panel-body">
<!--        <table class="table table-bordered">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th> No </th>-->
<!--                <th> Kode </th>-->
<!--                <th> Matakuliah </th>-->
<!--                <th> SKS </th>-->
<!--                <th> Jadwal </th>-->
<!--                <th> Kelas </th>-->
<!--                <th> Ruang </th>-->
<!--                <th> Quota </th>-->
<!--                <th> &nbsp; </th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            --><?php
//            $SEMESTER="";$n=0;
//            foreach ($listJadwal as $data){
//                if($SEMESTER!=$data['mtk_semester']){
//                    $SEMESTER=$data['mtk_semester'];
//                    echo "<tr><td colspan='9' class='text-center' style='font-size: 13px'> <b>SEMESTER $SEMESTER</b> </td></tr>";
//                }
//                $n++;
//
//                $jdwl=explode("|",$data['subJadwal']);
//                $jd = "";
//                foreach($jdwl as $k=>$v){
//                    $Info=explode('#',$v);
//                    $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
//                    $jd .=$ket."<br />";
//                }
//
//                $jadwa="";
//                echo"
//                <tr>
//                    <td>$n</td>
//                    <td>$data[mtk_kode]</td>
//                    <td>
//                        $data[mtk_nama]<br>
//                        &nbsp;&nbsp;&nbsp;$data[ds_nm]
//                    </td>
//                    <td>$data[mtk_sks]</td>
//                    <td> $jd </td>
//                    <td>$data[jdwl_kls]</td>
//                    <td>$data[jdwl_kls]</td>
//                    <td>$data[jdwl_kls]</td>
//                </tr>
//                ";
//
//
//            }
//
//
//            ?>
<!---->
<!---->
<!--            </tbody>-->
<!--        </table>-->


    </div>
</div>


<?=

    GridView::widget([
    'dataProvider' => $data,
    'rowOptions'=>function($data){
        $dis = false;
        if( ($data['AvKrs']==0)){$dis=true;}

        if(Funct::cekKrs($data['jdwl_id'])==1){
            return ['class' => 'success','style'=>'font-weight:bold'];
        }else{
            if($dis){return ['class' => 'danger','style'=>'font-weight:bold'];}
        }
    },
    'id'=>'krs-grid',
    'filterModel' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'width'=>'20%',
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
                $mk=$data["mtk_kode"];
                if($data['avKrsMk']==0){
                    $mk='<font><b>'.$data["mtk_kode"].'</b></font>';
                }
                return $mk;
            },
            'format'  => 'raw',
        ],
        [
            'header'  => 'Matakuliah',
            'value' => function($data) {
                return $data["mtk_nama"];
            },
            'format'  => 'raw',
        ],
        /*
       [
           'header'  => 'Program',
           'value' => function($data) {
              return $data["pr_nama"];
            },
           'format'  => 'raw',
       ],
        */
        [
            'header'  => 'Jadwal',
            'value' => function($data) {
                $jdwl=explode("|",$data['subJadwal']);
                $jd = "";
                foreach($jdwl as $k=>$v){
                    $Info=explode('#',$v);
                    $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
                    $jd .=$ket."<br />";
                }
                return $jd;

                $jm=Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
                if($data['avKrsTime']==0){
                    $jm='<font><b>'.$jm.'</b></font>';
                }
                return $jm;
            },
            'format'  => 'raw',
        ],
        [
            'header'  => 'Kelas',
            'value' => function($data) {
                return $data["jdwl_kls"];
            },
            'format'  => 'raw',
        ],
        /*
                       [
                           'header'  => 'Dosen',
                           'value' => function($data) {
                              return $data["ds_nm"];
                            },
                           'format'  => 'raw',
                       ],
        */
        [
            'header'  => 'SKS',
            'value' => function($data) {
                return $data["mtk_sks"];
            },
            'format'  => 'raw',
        ],
        [
            'header'  => 'Ruang',
            'value' => function($data) {
                return $data["rg_nama"];
            },
            'format'  => 'raw',
        ],
        [
            'value' => function ($data) {
                $a = $data['jdwl_id'];
                $dis = false;

                if($data['Ig']==0){
                    if( ($data['AvKrs']==0)){$dis=true;}
                }

                return
                    Html::checkbox('jdwl[]', Funct::cekKrs($data['jdwl_id'])==1 ? $data["jdwl_id"]:false, [
                        'value' => $data["jdwl_id"],'disabled'=>$dis,'id'=>'get'
                    ])
                    ."".Html::hiddenInput("sks[".$a."]",$data["mtk_sks"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ."".Html::hiddenInput("mtk[".$a."]",$data["mtk_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ."".Html::hiddenInput("mtk_nm[".$a."]",$data["mtk_nama"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ."".Html::hiddenInput("kr[".$a."]",$data["kr_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ."".Html::hiddenInput("nidn[".$a."]",$data["ds_nidn"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ."".Html::hiddenInput("ds_nm[".$a."]",$data["ds_nm"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ;
            },
            'format'  => 'raw',
        ],
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah '.$thn->kr->kr_nama,
        'after'=>Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Simpan', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave'])." ".
            Html::submitButton('<i class="glyphicon glyphicon-repeat"></i> Reset', ['class' => 'btn btn-info'],['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$th]),
    ],
    'toolbar'=>false
]);

?>
