<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use app\models\BobotNilaiDosen;
use kartik\select2\Select2;
use kartik\editable\Editable;
use kartik\export\ExportMenu;


$Columns =[
        ['class' => 'yii\grid\SerialColumn'],
        [   //Attribute Ini Jangan di Hapus ya....   
            'width' => '5%',
            'label' => 'KRS ID',
            'attribute' => 'krs_id',
        ],
        [     
            'width' => '5%',
            'label' => 'NIM',
            'attribute' => 'mhs_nim',
        ], 
        [     
            'width' => 'auto',
            'label' => 'Mahasiswa',
            'attribute' => 'Nama',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 1',
            'attribute' => 'krs_tgs1',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 2',
            'attribute' => 'krs_tgs2',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tugas 3',
            'attribute' => 'krs_tgs3',
        ], 
        [     
            'width' => '5%',
            'label' => 'Tambahan',
            'attribute' => 'krs_tambahan',
        ], 
        [     
            'width' => '5%',
            'label' => 'Quis',
            'attribute' => 'krs_quis',
        ], 
        [     
            'width' => '5%',
            'label' => 'UTS',
            'attribute' => 'krs_uts',
        ], 
        [     
            'width' => '5%',
            'label' => 'UAS',
            'attribute' => 'krs_uas',
        ],
        [
            'label' =>'Total',
            'value' => function($model){
                return (floatval($model['krs_tot']) < 1) ? 
                        $model['total']  : 
                        $model['krs_tot'] ;
            }
        ],
        [
            'label' =>'Grade',
            'value' => function($model) use($BN){
                return (floatval($model['krs_tot']) < 1) ? 
                        BobotNilaiDosen::Grade($model['total'],$BN) : 
                        $model['krs_grade'] ;
            }
        ]
        
    ];
/*echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $Columns,
    'fontAwesome' => true,
    'dropdownOptions' => [
        'label' => 'Export All',
        'class' => 'btn btn-default'
    ]
]) . "<hr>\n";*/

$this->registerJs("$('#InputNilai').Tabledit({
    url: 'input-nilai',
    deleteButton: false,
    saveButton: true,
    restoreButton: false,
    autoFocus: true,

    hideIdentifier: false,
    buttons: {
        edit: {
            class: 'btn btn-sm btn-primary',
            html: '<span class=\"glyphicon glyphicon-pencil\"></span> &nbsp EDIT',
            action: 'edit'
        },
         delete: {
            class: 'btn btn-sm btn-warning',
            html: '<span class=\"glyphicon glyphicon-refresh\"></span> &nbsp Default',
            action: 'default'
        }
    },
    columns: {
        identifier: [1, 'krs_id'],
        editable: [[4, 'krs_tgs1'],[5, 'krs_tgs2'],[6, 'krs_tgs3'],[7, 'krs_tambahan'],[8, 'krs_quis'],[9, 'krs_uts'],[10, 'krs_uas']]
    },
    onDraw: function() {
       
    },
    onSuccess: function(data, textStatus, jqXHR) {
       
    },
    onFail: function(jqXHR, textStatus, errorThrown) {
      
    },
    onAlways: function() {
       $.pjax.reload({container: '#pjaxInputNilai'});
    },
    onAjax: function(action, serialize) {
        if (action=='default') {
            $.pjax.reload({container: '#pjaxInputNilai'});
        }
        
    }
});
    
   ", yii\web\View::POS_END, 'InputNilai');

?>
<div class="panel panel-primary">
<div class="panel-heading">
 Input Nilai Mahasiswa || <?=@$BN['mtk_kode'].' '.@$BN['mtk_nama']?>
</div>
  <div class="panel-body">

<?= GridView::widget([     
    'dataProvider'=> $dataProvider,
    //'filterModel' => $searchModel,
    'tableOptions'=>['id'=>'InputNilai'],
    'columns' => $Columns,
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
]) ?>     
  </div>
  <div class="panel-footer"></div>
</div>