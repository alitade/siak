<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;

$this->title = 'Mahasiswa '.($KR?" (Perwalian ".$KR['nm'].") ":"");
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Filter Data Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
echo $this->render('perwalian_search', ['model' =>$searchModel,'kr'=>($KR?$KR['kd']:0)]);
Modal::end();
?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
if($searchModel->jr_id){echo "<span class='badge bg-info'> $searchModel->jr_id </span> ";}
?>
<p> </p>
<div class="mahasiswa-index">
    <?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL,'method'=>'get','action' => ['perwalian'],]);
    $krkd = null;
    if(isset($_GET['perwalian']['kr'])){$krkd=$_GET['perwalian']['kr'];}

    echo Form::widget([
        'form' => $form,
        'formName'=>'perwalian',
        'columns' =>1,
        'attributes' => [
            'tahun'=>[
                'label'=>'',
                'attributes'=>[
                    'kr' =>[
                        'label'=>false,
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' =>app\models\Funct::AKADEMIK(),
                            'options' => ['fullSpan'=>6,'placeholder' => 'Pilih Tahun Akademik'],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_RAW,
                        'value'=>
                            Html::submitButton(Yii::t('app', 'Cari Data Mahasiswa'),['class' =>'btn btn-primary','style'=>'text-align:right'])
                            ." ".(Funct::acc('/pengajar/create') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Data Pengajar', ['/pengajar/create'], ['class' => 'btn btn-success']) : "")
                    ],
                ],
            ],
        ]
    ]);
    ActiveForm::end();
    echo"<p></p>";
    if($KR):
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar'=> [
            [
                'content'=>
                #Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['report-mahasiswa'],['class'=>'btn btn-info'])
                    Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to().'&c=1',['class'=>'btn btn-info','target'=>'_blank'])
            ],
            '{toggleData}',
            (!Funct::acc('gridview')?false:"{export}"),
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'	=> 'jr_id',
                'value'		=> function($model){return @$model->jr->jr_jenjang." ".@$model->jr->jr_nama." (".$model->pr->pr_nama.")";},
                'group'     =>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'label'=>'NIM | Nama',
                'attribute' => 'mhs_nim',
                'format'=>'raw',
                'value'=>function($model){
                    return " <b>".$model->mhs_nim."</b> | ".$model->Nama;
                }
            ],
            /*
            [
                'attribute' => 'mhs_nim',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_nim;
                    return Html::a($model->mhs_nim,['pindah','id'=>$model->mhs_nim],['target'=>"_blank"]);
                }
            ],*/
            [
                'label'=>'Angkatan (Kurikulum)',
                'attribute' => 'mhs_angkatan',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->mhs_angkatan." (".$model->thn.")";
                }

            ],
            [
                'label'=>'Matakuliah',
                'attribute'=>'kr.kode',

                'width'=>'20%',
            ],
            [
                'label'=>'Reg.',
                'attribute' => 'thn',
                'format'=>'raw',
                'value'		=> function($model){
                    return ($model->reg?'<i class="glyphicon glyphicon-ok"></i></i>':'<i class="glyphicon glyphicon-remove"></i>');
                },
                'filter'=>['N','Y'],
            ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'template'=>'<li>{jdwl}{krs}</li>'
                ,
                'buttons' => [
                    'jdwl'=> function ($url, $model, $key)use($KR) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> Jadwal',['mhs-jadwal','nim' => $model->mhs_nim,'kr'=>$KR['kd']],['target'=>'_blank']);
                    },
                    'krs'=> function ($url, $model, $key)use($KR) {
                        #if(!Funct::acc('/mhs/view')){return false;}
                        return Html::a('<i class="fa fa-list"></i> KRS',['mhs-krs','nim' => $model->mhs_nim,'kr'=>$KR['kd']],['target'=>'_blank']);
                    },

                ],
                'dropdown'=>true,
                'dropdownOptions'=>['class'=>'pull-right'],
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
            'before'=>Html::a('<i class="glyphicon glyphicon-search"></i> Filter', ['index'], ['class' => 'btn btn-success','id'=>'popupModal' ]),
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'<span style="font-size:14px;font"><i><i class="fa fa-users"> </i> '.$this->title.'</span></i>',
        ]
    ]);
    endif;
    ?>
</div>
