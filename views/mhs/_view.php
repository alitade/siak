<?php
use kartik\detail\DetailView;
use app\models\Funct;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

$agama=[1=>'Islam','Protestan','Katolik','Hindu','Budha'];


$TRANSKRIP = new \yii\data\SqlDataProvider([
    'sql'=>" Exec dbo.detailnilai '$model->mhs_nim'",
    'db'=>'db2',
    'pagination' => ['pageSize' => 0,],
]);

#Funct::v($TRANSKRIP);
if(Funct::acc('/mhs/transkrip')){
    Modal::begin([
        'header' => '<i class="fa fa-list"></i> Nilai Transkrip',
        'id'=>'modals-tr',
        'size'=>'modal-lg',
        'headerOptions'=>['class'=>'bg-primary'],
    ]);

    $ipk=Yii::$app->db2->createCommand("select dbo.ipk('".$model->mhs_nim."') ipk")->queryOne();
    $SKS=0;
    foreach ($TRANSKRIP->getModels() as $d){$SKS+=$d['sks'];}

    echo GridView::widget([
        'dataProvider' => $TRANSKRIP,
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
        'panel'=>[
            'heading'=>false,
            'before'=>"<b>IPK: ".number_format($ipk['ipk'],2)."<span class='pull-right'>Total SKS: ".$SKS." SKS</span>",
            'footer'=>false,
            'after'=>false,
        ],
        'toolbar'=>false
    ]);
    Modal::end();
}



Modal::begin(['header' =>false,'id' => 'modals',]);
echo $dataMatkul;
Modal::end();

?>
<p>
    <?= (!Funct::acc('/mhs/transkrip')?"":Html::a('<i class="fa fa-list"></i> Transkrip', ['/mhs/khs', 'id' => $model->mhs_nim],['class'=>'btn btn-success','id'=>'popupModal-tr'])) ?>
    <?= (!Funct::acc('/mhs/people')?"":Html::a('<i class="fa fa-pencil"></i> Update Biodata', ['/mhs/people', 'id' => $model->mhs_nim],['class'=>'btn btn-success']))?>
    <?= (!Funct::acc('/mhs/mk-kr')?"":Html::a('<i class="fa fa-book"></i> Kurikulum Matakuliah', ['#'],['class'=>'btn btn-success','id'=>'popupModal']))?>
</p>




<?= DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Mahasiswa : ' . @$model->mhs->people->Nama." ( ".@$model->mhs->status_mhs." ) ",
        'type'=>DetailView::TYPE_PRIMARY,
    ],
    'attributes' => [
    [
        // 1
        'columns'=>[
            [
                'label'=>'Nama (NPM)',
                'value'=>@$model->mhs->people->Nama.' ('.$model->mhs_nim.')',
                'displayOnly'=>true,
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'label'=>'Asal Sekolah ( Tahun Lulus)',
                'value'=>@$model->mhs->people->asal_sekolah.' ('.@app\models\Funct::TANGGAL(@$model->mhs->people->tahun_lulus).')',
                'displayOnly'=>true,
            ],
        ],
    ],
    [
        // 2
        'columns'=>[
            [
                'label'		=>'Jurusan (Program)',
                'attribute'	=>'mhs_nim',
                'value'		=>app\models\Funct::JURUSAN()[$model->jr_id].' ('.$model->pr->pr_nama.')',
                'displayOnly'=>true,
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'label'=>'No. KTP',
                'value'=>@$model->mhs->people->no_ktp,
                'displayOnly'=>true,
            ],
        ],
    ],
    [	// 3
        'columns'=>[
            [
                'attribute'=>'mhs_angkatan',
                'value'=>$model->mhs_angkatan." / ".$model->mhs->kurikulum,
                'valueColOptions'=>['style'=>'width:30%']
            ],
            [
                'label'=>'Tempat / Tgl. Lahir',
                'value'=>@$model->mhs->people->tempat_lahir.' / '.@app\models\Funct::TANGGAL(@$model->mhs->people->tanggal_lahir),
                'displayOnly'=>true,
            ],
        ],
    ],
    [	// 4
        'columns'=>[
            [
                'label'=>'No. Tlp.',
                'value'=>@$model->mhs->people->no_telepon,
                'displayOnly'=>true,
                'valueColOptions'=>['style'=>'width:30%'],
            ],
            [
                'label'=>'Agama',
                'value'=>(isset($agama[@$model->mhs->people->agama])? $agama[@$model->mhs->people->agama]:@$model->mhs->people->agama),
                'displayOnly'=>true,
            ],
        ],
    ],
    [	// 5
        'columns'=>[
            [
                'label'=>'Dosen Wali',
                'attribute'=>'jr_id',
                'value'=>@app\models\Funct::DSN(1,'ds_id')[@$model->ds_wali],
                'valueColOptions'=>['style'=>'width:30%'],
            ],
            [
                'label'=>'Alamat Tinggal',
                'value'=>
                    @app\models\Funct::getAlamat($model->mhs_nim).', '
                    .@$model->mhs->people->kota.', '
                    .@$model->mhs->people->propinsi.', '
                    .@$model->mhs->people->kode_pos.' '
                    ,
                'displayOnly'=>true,
            ],
        ],
    ],
    [	// 6
        'columns'=>[
            [
                'label'=>false,
                'value'=>false,//$model->pr->pr_nama,
                'valueColOptions'=>['style'=>'width:30%']

            ],
            [
                'label'=>'Nama Ibu Kandung',
                'value'=>@$model->mhs->people->ibu_kandung,
                'displayOnly'=>true,
            ],
        ],
    ],

],
'enableEditMode'=>false,
])
?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");

$this->registerJs("$(function() {
   $('#popupModal-tr').click(function(e) {
     e.preventDefault();
     $('#modals-tr').modal('show').find('.modal-content').html(data);
   });
});");
?>
