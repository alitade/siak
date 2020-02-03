<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;

$this->title = $model->jr->jr_jenjang.' '.$model->jr->jr_nama.' : '.$model->ds->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Detail', 'url' => ['mhs','id'=>$model->ds_id]];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>['tabindex' => false],
    'id'=>'modals',
    'header'=>'Pencarian Data Mahasiswa',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']
]);
echo $this->render('mhs__search', ['model' => $searchModel,'subAkses'=>$subAkses,'DSID'=>$model,'URL'=>Url::to(['/dosen-wali/view','id'=>$model->jr_id,'id1'=>$model->ds_id])]);
Modal::end();


?>

<div style="padding:14px 0px 3px 14px;border-bottom:solid 1px rgba(0,0,0,0.3);">
    <span style="font-size:16px;font-weight:bold"> <?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama ?> : <?= $model->ds->ds_nm ?></span>
    <div class="pull-right">

    </div>
    <div style="clear: both"></div>
</div>
<p></p>
<?php

$btn = Html::a('<i class="fa fa-search"></i> Pencarian Data Mahasiswa', ['#'], ['class' => 'btn btn-info','id'=>'popupModal' ]);
$nCari=0;
$ketCari='';
if(@$searchModel->pr_kode){$ketCari.=' <span class="label label-info label-sm" style="font-size:14px;"><i style="color:#000">Program: '.$searchModel->pr->pr_nama.'</i> </span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_nim){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> NPM: --'.$searchModel->mhs_nim.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->Nama){$ketCari.=' <span class="label label-info" style="font-size:14px;"><i style="color:#000"> Nama: --'.$searchModel->mhs_nim.'-- </i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->mhs_angkatan){$ketCari.=' <span class="label label-info"style="font-size:14px;"><i style="color:#000">Angkatan:'.$searchModel->mhs_angkatan.'</i></span>&nbsp;'; $nCari+=1;}
if(@$searchModel->thn){$ketCari.=' <span class="label label-info" style="font-size: 14px;;"><i style="color:#000"> Kurikulum: '.$searchModel->thn.'</i></span>&nbsp;'; $nCari+=1;}
if($nCari>0){
    $btn .=" ".Html::a('<i class="fa fa-refresh"></i> Reset Pencarian',['mhs-create','id'=>$model->jr_id,'id1'=>$model->ds_id],['class'=>'btn btn-info']);
    echo $ketCari='<p></p><div>'.$ketCari."&nbsp;"."</div><p></p>";
}
#echo "$ketCari <p></p>";


    $form1 = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig'=>[
            'labelSpan'=>2,'options'=>['onSubmit'=>'return confirm("Hapus Data?")']]
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar'=> [
            ['content'=>false],
            '{toggleData}',
            (!Funct::acc('gridview')?false:"{export}"),
        ],
        'columns' => [
            [
                'class'=>'kartik\grid\CheckboxColumn',
                'headerOptions'=>[
                    'class'=>'kartik-sheet-style'
                ],
                'visible'=>Funct::acc('/dosen-wali/mhs-del'),
            ],
            [
                'class' => 'kartik\grid\SerialColumn',
                'width'=>'1%',
            ],
            [
                'attribute'	=> 'jr_id',
                'value'	=> function($model){return @$model->jr->jr_jenjang.' '.$model->jr->jr_nama;},
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute'	=> 'pr_kode',
                'width'=>'10%',
                'value'=>function($model){return $model->pr->pr_nama;},
                'group'=>true,  // enable grouping
                'subGroupOf'=>1, // supplier column index is the parent group,
            ],
            [
                'attribute' => 'mhs_nim',
                'value'=>function($model){
                    return $model->mhs_nim;
                }
            ],
            [
                'attribute' => 'Nama',
                'label'=>'Nama',
                'value'=>function($model){
                    return $model->Nama;
                }
            ],
            [
                'label'=>'Angkatan',
                'attribute'=>'thn',
                'value'=>function($model){
                    return $model->mhs_angkatan.' ('.$model->thn.')';
                }

            ],
            [
                'label'=>'Ket.',
                'attribute'=>'kr.kode',
                'value'=>function($model){
                    return ($model->dft->keterangan?:"SMA/SMK/SEDERAJAT");
                },
                'width'=>'20%',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'<li>{view}</li><li>{delete}</li>',
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
                'buttons' => [
                    'delete'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/dosen-wali/mhs-del')){return false;}
                        return Html::a('<i class="fa fa-trash"></i> Delete',['/dosen-wali/mhs-del','id' => $model->mhs_nim,]);
                    },
                    'view'=> function ($url, $model, $key) {
                        if(!\app\models\Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-eye"></i> Detail',['/mhs/view','id' => $model->mhs_nim],['title'=>'Detail Mahasiswa','target'=>'_blank']);
                    },

                ],
            ],        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'toolbar'=>false,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-users"></i> Daftar Mahasiswa Didik '.$model->ds->ds_nm,
            'header'=>true,
            'before'=>
                $btn.' '
                .(!Funct::acc('/dosen-wali/mhs-create')?" ":Html::a("<i class='fa fa-plus'></i> Tambah Data Mahasiswa ",['/dosen-wali/mhs-create','id'=>$model->jr_id,'id1'=>$model->ds_id],["class"=>'btn btn-success']))." "
                .(!Funct::acc('/dosen-wali/mhs-del')?" ":Html::submitButton("<i class='fa fa-trash'></i> Hapus Dosen Wali Dari Data Terpilih",["class"=>'btn btn-danger','name'=>'u']))." "
            ,
        ]
    ]);
    ActiveForm::end();

$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");


?>
