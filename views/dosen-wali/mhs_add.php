<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;

$this->title = 'Update Dosen Wali Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Detail', 'url' => ['view','id'=>$model->jr_id,'id1'=>$model->ds_id]];
$this->params['breadcrumbs'][] = $this->title;

 Modal::begin([
    'options'=>['tabindex' => false],
    'id'=>'modals',
    'header'=>'Pencarian Data Mahasiswa',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary']
]);
echo $this->render('mhs__search', ['model' => $searchModel,'subAkses'=>$subAkses,'DSID'=>$model,'JRID'=>$JRID]);
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

#if($dataProvider->count>0){
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
                'visible'=>Funct::acc('/dosen-wali/mhs-create'),
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
                'attribute'=>'ds_wali',
                'label'=>'Dosen Wali.',
                'value'=>function($model){
                    return $model->ds_wali;
                }

            ],
            [
                'attribute'	=> 'ws',
                'width'     => '5%',
                'format'    => 'raw',
                'value' 	=> function($model){
                    return ($model->ws?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
                },
                'filter'=>['N','Y'],

            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'toolbar'=>false,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<i class="fa fa-users"></i> Daftar Calon Mahasiswa Didik',
            'header'=>true,
            'before'=>
                $btn.' '.
                (!Funct::acc('/dosen-wali/mhs-create')?" ":Html::submitButton("<i class='fa fa-pencil'></i> Update Dosen Wali Mahasiswa Terpilih",["class"=>'btn btn-success','name'=>'u']))." "
            ,
        ]
    ]);
    ActiveForm::end();


#}
?>


<?php


$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");


?>
