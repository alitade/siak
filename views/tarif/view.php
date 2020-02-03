<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 */

$this->title = $model->kode_tarif;
$this->params['breadcrumbs'][] = ['label' => 'Tarifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'<i class="fa fa-plus"></i> Tambah Detail Tarif',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modalsAdd',
    'clientOptions'=>['show'=>($model->getErrors()?true:false),],

]);
Modal::end();
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
echo $this->render('/tarifdetail/form',['model'=>$mDetail]);
echo '<div class="pull-right">'.Html::submitButton('<i></i> Simpan',['class'=>'btn btn-success pull-right']).' </div>';
echo'<div class="clearfix"></div>';
ActiveForm::end();

?>



<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Tarif : <?= $model->kode_tarif ?> </span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:bold;font-family:'Tahoma'">
            <?php
            if($qKriteria){
                foreach($qKriteria as $d){

                    if($d['n']==1){echo "".($d[item]?:"Seluruh Konsultan").' | ';}
                    if($d['n']==2){echo "".($d[item]?:"Seluruh Fakultas").' | ';}
                    if($d['n']==3){echo "".($d[item]?:"Seluruh Jurusan").' | ';}
                    if($d['n']==4){echo "".($d[item]?:"Seluruh Program Perkuliahan").' | ';}
                    if($d['n']==5){echo "".($d[item]?:"Seluruh Angkatan").' | ';}
                    if($d['n']==6){echo "".($d[item]?:"Seluruh Kurikulum").' | ';}
                    if($d['n']==7){echo "".($d[item]?"Mahasiswa $d[item]":"Seluruh Mahasiswa").'';}
                }
            }
            ?>

        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <tr>
                <th>Jenis Tarif: <?= '' ?> </th>
                <th>Masa Penagihan: <?= $model->maksimum." ".$model->satuan ?> </th>
            </tr>
            </thead>
        </table>

        <?= "" ?>
        <?php #if($dataProvider->count>0): ?>

            <div class="tarifdetail-index">
                <?php Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'attribute'=>'item',

                        ],
                        [
                            'attribute'=>'dpp',
                            'label'=>'Biaya'
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'condensed'=>true,
                    'floatHeader'=>true,
                    'panel' => [
                        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                        'type'=>'info',
                        'before'=>Html::a('<i class="fa fa-plus"></i> Add', ['create'], ['class' => 'btn btn-success','id'=>'_modalsAdd']),
                        'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
                        'showFooter'=>false
                    ],
                ]);
                Pjax::end(); ?>
            </div>

        <?php #endif;
        ?>
    </div>


</div>


<?php
$this->registerJs("$(function() {
   $('#_modalsAdd').click(function(e) {
     e.preventDefault();
     $('#modalsAdd').modal('show').find('.modal-content').html(data);
   });
});");
