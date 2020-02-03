<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<?php 
use yii\helpers\Html;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use app\models\Kalender;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;

$val=['jd'=>$JDWLID,'mk'=>$KDMK];
$this->title = $MHS->mhs_nim.": ".$MHS->mhs->people->Nama;
$this->params['breadcrumbs'][] = $this->title;

#Yii::$app->vd->vd()['qKRS'];
?>

<?php
Modal::begin([
    'header' => ' KETERANGAN ',
    'id'=>'modals',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
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
<?php Modal::end();?>

<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:14px;font-weight:bold;font-family: 'arial'"> <?= $MHS->mhs_nim.": ".$MHS->mhs->people->Nama ?></span>
    <div class="pull-right"><?= $MHS->jr->jr_jenjang.". ".$MHS->jr->jr_nama." (".$MHS->pr->pr_nama.")" ?></div>
    <div style="clear: both"></div>
</div>
<p></p>
<?php
?>


<?php
$query = "SELECT * FROM regmhs WHERE nim ='".$MHS->mhs_nim."' AND tahun='".$KR->kr_kode."'";
$hash = Yii::$app->db1->createCommand($query)->queryOne();

if(!$hash):?> <div class="alert alert-danger"> Mahasiswa Belum Melakukan Registrasi</div><?php endif;

  echo GridView::widget([
        'dataProvider' => $data,
		'rowOptions'=>function($data)use($val){
            $dis    = false;
            $vq = false;
            if(Yii::$app->vd->vd()['qKRS']==1){if($data['mhs']>=$data[kapasitas]){$vq=true;}}
            if($data['Ig']==0){if($data['S']==0||$vq||$val['mk'][$data['mtk_kode']]){$dis=true;}}

			if(isset($val['jd'][$data['jdwl_id']])){
				return ['class' => 'success','style'=>'font-weight:bold'];
			}else{
				if($dis){return ['class' => 'danger','style'=>'font-weight:bold',];}
			}
		},
        'id'=>'krs-grid',
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'mergeHeader' => true,
            ],
            [
                'width'=>'20%',
                'format'=>'raw',
                'value'=>function($data){
                    return " <span style='font-size: 16px;'>Semester ".$data["mtk_semester"]."</span>";
                },
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'header'  => 'Jadwal | Ruangan',
                'mergeHeader' => true,
                'value' => function($data) {
                    $jdwl=explode("|",$data['jadwal']);
                    $jd = "";
                    foreach($jdwl as $k=>$v){
                        $Info=explode('#',$v);
                        $ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
                        $jd .=$ket."<br />";
                    }
                    return "<b>$jd</b>";

                    $jm=Funct::getHari()[$data["jdwl_hari"]].", ". $data["jdwl_masuk"]."-".$data["jdwl_keluar"];
                    if($data['avKrsTime']==0){
                        $jm='<font><b>'.$jm.'</b></font>';
                    }
                    return $jm;
                },
                'format'  => 'raw',
            ],
            [
                'header'=>'<i class="fa fa-info"></i>',
                'width'=>'1%',
                'mergeHeader' => true,
                'format'=>'raw',
                'value'=>function($data){return  "<span><b>[$data[Status]]</b></span> ";}

            ],
            [
                'header'  => 'Kode',
                'mergeHeader' => true,
                'width'=>'1%',
                'value' => function($data){
                    $mk="<span class='badge' style='background:".($data['KR']==1?'green':'red').";'>$data[mtk_kode]</span>";
                    return $mk;
                },
                'format'  => 'raw',
            ],
            [
                'header'  => 'Matakuliah (Kls)',
                'mergeHeader' => true,
                'format'=>'raw',
                'value' => function($data){return "<b>".$data[mtk_nama]." ($data[jdwl_kls])</b>";},
            ],
            [
                'header'  => 'SKS',
                'mergeHeader' => true,
                'width'=>'1%',
                'value' => function($data) {
                    return $data["mtk_sks"];
                },
                'format'  => 'raw',
            ],
            [
                'header'  => '<i class="fa fa-home"></i>',
                'width'=>'1%',
                'mergeHeader' => true,
                'value' => function($data) {
                    return $data["kapasitas"];
                },
                'format'  => 'raw',
            ],

            [
                'header'  => '<i class="fa fa-users"></i>',
                'width'=>'1%',
                'mergeHeader' => true,
                'value' => function($data) {
                    return $data["mhs"];
                },
                'format'  => 'raw',
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,    
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=>'<span style="font-size: 14px"><i class="fa fa-navicon"></i> Jadwal Kuliah '.$MHS->jr->jr_jenjang.' '.$MHS->jr->jr_nama." (".$MHS->pr->pr_nama.') | '.$KR->kr_kode.'-'.$KR->kr_nama.'</span>',
            'after'=>false,
            'before'=>
                Html::a("<i class='fa fa-info-circle'></i> INFO ",["#"],['class'=>'btn btn-primary btn-sm','id'=>'popupModal']).' '
                .Html::a("<i class='fa fa-file'></i> KRS ",["mhs-krs",'nim'=>$MHS->mhs_nim,'kr'=>$KR->kr_kode],['class'=>'btn btn-primary btn-sm']),
            'footer'=>false,
        ],
        'toolbar'=>false,
    ]); 



  ?>

<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
?>
