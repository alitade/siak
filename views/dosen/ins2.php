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
    <tr class="danger"><th>Jadwal yang telah diterima / ditolak oleh dosen tidak bisa diubah/dihapus oleh mahasiswa</th></tr>
</table>
<?php Modal::end();?>

<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:14px;font-weight:bold;font-family: 'arial'"> <?= $MHS->jr->jr_jenjang.' '.$MHS->jr->jr_nama." (".$MHS->pr->pr_nama.') | '.$KR->kr_kode.'-'.$KR->kr_nama ?></span>
    <!--div class="pull-right"><?= $MHS->jr->jr_jenjang.". ".$MHS->jr->jr_nama." (".$MHS->pr->pr_nama.")" ?></div-->
    <div style="clear: both"></div>
</div>
<p></p>

<?php
$db = Yii::$app->db1;
$query = "SELECT * FROM regmhs WHERE nim ='".$MHS->mhs_nim."' AND tahun='".$KR->kr_kode."'";

$hash = $db->createCommand($query)->queryOne();

if(!$hash):?> <div class="alert alert-danger"> Mahasiswa Belum Melakukan Registrasi</div><?php endif;

$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_VERTICAL,
    'id'=>'create-krs','action' => Url::to(['mhs-krs-proc']),
    'method'=>'post'
]);

echo Html::hiddenInput("nim",$MHS->mhs_nim,["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"]);
echo Html::hiddenInput("kr",$KR->kr_kode,["style"=>"width:135px; border-color:white; background-color:white","readOnly"=>"true"]);

echo GridView::widget([
        'dataProvider' => $data,
        'filterModel'=>false,
        'id'=>'krs-grid',
          'columns' => [
              [
                  'class' => 'kartik\grid\SerialColumn',
              ],
              [
                  'class'=>'kartik\grid\CheckboxColumn',
                  'headerOptions'=>['class'=>'kartik-sheet-style'],
                  'checkboxOptions' => function($data, $key, $index, $column) {
                      return ['value' => $data['krs_id']];
                  }
                  #'visible'=>Funct::acc('/matkul-kr/delete'),
              ],
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
                  'label'  => 'Jadwal | Ruangan',
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
                  'mergeHeader' => true,
                  'width'=>'1%',
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
                  'value' => function($data) {return $data["mtk_sks"];},
                  'format'  => 'raw',
                  'pageSummary'=>true,
                  'pageSummaryFunc'=>GridView::F_SUM,
              ],
              [
                  'header'  => '',
                  'mergeHeader' => true,
                  'width'=>'1%',
                  'value' => function($data) {
                    $s='<span class="badge"  style="background:blue">pending</span>';
                      if($data['krs_stat']==='1'){$s='<span class="badge" style="background:green">Terima</span>';}
                      if($data['krs_stat']==='0'){$s='<span class="badge" style="background:red">Tolak</span>';}

                    return $s;
                  },
                  'format'  => 'raw',
              ],
          ],
        'responsive'=>true,
        'showPageSummary' => true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'(KRS) '.$MHS->mhs_nim.": ".$MHS->mhs->people->Nama." ",
            'after'=>
                Html::submitButton('<i class="fa fa-lock"></i> Terima ', ['class' => 'btn btn-success','id' => 'button', 'name' => 'app'])." "
                .Html::submitButton('<i class="fa fa-minus-circle"></i> Tolak ', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'cnc'])." "
                .Html::submitButton('<i class="fa fa-trash"></i> Hapus ', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'del'])." "
            ,
            'before'=> Html::a("<i class='fa fa-info-circle'></i> INFO ",["#"],['class'=>'btn btn-primary btn-sm','id'=>'popupModal'])
            .' '.Html::a("<i class='fa fa-list'></i> Jadwal Perkuliahan ",["mhs-jadwal",'nim'=>$MHS->mhs_nim,'kr'=>$KR->kr_kode],['class'=>'btn btn-primary btn-sm',]),
            'footer'=>false,
        ],
        'toolbar'=>false,
    ]);
ActiveForm::end();
?>

<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
?>
