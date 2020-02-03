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

$this->title = 'INPUT KRS';
#$this->params['breadcrumbs'][] = ['label' => 'KRS', 'url' => ['krs/admin-mhs','id'=>$mHkrs->id]];
$this->params['breadcrumbs'][] = $this->title;
$app=($mHkrs->app?:0);
?>

<?php
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'id'=>'create-krs',
    'action' => Url::to(['krs/admin-proc','id'=>$model->mhs_nim]),
    'method'=>'post'
]);

#Funct::v($data);
?>

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
                $mk=$data["mtk_nama"];
                if(Yii::$app->vd->vid(66,1,true)){
                    $mk.="<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user'></i> $data[ds_nm]";
                }
                return '<span'.($data['krs_stat']==2?"style='text-decoration: line-through'":"" ).' >'.$mk.'</span>';

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
            'width'=>'1%',
            'value' => function($data) {
                if(Yii::$app->vd->vid(66,1,true)){
                    return $data['kapasitas'];
                }
                return '-';
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
                $a = $data['jdwl_id'];
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

                return
                    Html::checkbox('jdwl[]', isset($JD['ID'][$data['jdwl_id']])? $data["jdwl_id"]:false, [
                        'value' => $data["jdwl_id"],'disabled'=>$dis,'id'=>'get'
                    ])
                    ." ".Html::hiddenInput("s[".$a."]",$data['Status'],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])

                    #."".Html::hiddenInput("sks[".$a."]",$data["mtk_sks"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    #."".Html::hiddenInput("mtk[".$a."]",$data["mtk_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    #."".Html::hiddenInput("mtk_nm[".$a."]",$data["mtk_nama"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    #."".Html::hiddenInput("kr[".$a."]",$data["kr_kode"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    #."".Html::hiddenInput("nidn[".$a."]",$data["ds_nidn"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    #."".Html::hiddenInput("ds_nm[".$a."]",$data["ds_nm"],["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"])
                    ;
            },
            'format'  => 'raw',
        ],
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    'layout' =>false,
    'panel'=>[
        'type'=>GridView::TYPE_DEFAULT,
        'heading'=>'<span style="text-transform:uppercase;font-size:16px;font-weight: bold"><i class="fa fa-th-list"></i> JADWAL PERKULIAHAN SEMESTER '.$mKr->kr->kr_nama." (MAKS. $maxSks[nil] SKS)".'</span>'
        #.Html::a("<i class='fa fa-question-circle' style='font-size: 20px'></i>",["#"],['class'=>'','id'=>'popupModal'])

        ,
        'before'=>
            '<span style="font-size: 16px;font-weight: bold"> '.$model->dft->bio->Nama .' ['.$model->mhs_nim.'] '.$model->dft->bio->agama.' 
            <div class="pull-right"> '.$model->jr->jr_jenjang.' '.$model->jr->jr_nama." (".$model->pr->pr_nama." | ".($model->dft->keterangan?:'Mahasiswa Baru').") ".'</div>
            </span>
            '
        ,
        'footer'=>false,
        'after'=>Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Simpan', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave'])." ".
            Html::submitButton('<i class="glyphicon glyphicon-repeat"></i> Reset', ['class' => 'btn btn-info'],['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$th]),
    ],
    'toolbar'=>false
]);
?>
<?php ActiveForm::end();?>