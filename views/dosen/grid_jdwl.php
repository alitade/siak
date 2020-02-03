<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

$this->registerJs("$('#Nilai').Tabledit({
    url: 'bobot',
    deleteButton: true,
    saveButton: true,
    restoreButton: false,
    autoFocus: true,

    hideIdentifier: false,
    buttons: {
        edit: {
            class: 'btn btn-sm btn-primary',
            html: '<span class=\"glyphicon glyphicon-edit\"></span> &nbsp Ubah',
            action: 'edit'
        },
         delete: {
            class: 'btn btn-sm btn-info',
            html: '<span class=\"glyphicon glyphicon-refresh\"></span> &nbsp Default',
            action: 'default'
        }
    },
    columns: {
        identifier: [0, 'id'],
        editable: [[3, 'nb_tgs1'],[4, 'nb_tgs2'],[5, 'nb_tgs3'],[6, 'nb_tambahan'],[7, 'nb_quis'],[8, 'nb_uts'],[9, 'nb_uas'],[10, 'B'],[11, 'C'],[12, 'D'],[13, 'E']]
    },
    onDraw: function() {
       
    },
    onSuccess: function(data, textStatus, jqXHR) {
       
    },
    onFail: function(jqXHR, textStatus, errorThrown) {
      
    },
    onAlways: function() {
       $.pjax.reload({container: '#pjaxNilai'});
    },
    onAjax: function(action, serialize) {
        if (action=='default') {
            $.pjax.reload({container: '#pjaxNilai'});
        }
        
    }
});
    
   ", yii\web\View::POS_END, 'Bobot');

?>
<div class="panel panel-primary">
<div class="panel-heading">
 Konfigurasi Bobot Nilai
</div>
  <div class="panel-body">

<?= GridView::widget([
     
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions'=>['id'=>'Nilai'],
    'columns' => [
         
        [
            'visible' => 'false',         
            'width' => '5%',
            'attribute' => 'id','format' => 'raw',
            'value' => function($model){
                    return $model->id;
            },
        ], 
        'mtk_kode', 
        'mtk_nama',
        [         
            'width' => '5%',
            'attribute' => 'nb_tgs1','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_tgs1)) ? 0 : $model->nb_tgs1;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_tgs2','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_tgs2)) ? 0 : $model->nb_tgs2;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_tgs3','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_tgs3)) ? 0 : $model->nb_tgs3;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_tambahan','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_tambahan)) ? 0 : $model->nb_tambahan;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_quis','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_quis)) ? 0 : $model->nb_quis;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_uts','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_uts)) ? 0 : $model->nb_uts;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'nb_uas','format' => 'raw',
            'value' => function($model){
                    return (empty($model->nb_uas)) ? 0 : $model->nb_uas;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'B','format' => 'raw',
            'value' => function($model){
                    return (empty($model->B)) ? 0 : $model->B;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'C','format' => 'raw',
            'value' => function($model){
                    return (empty($model->C)) ? 0 : $model->C;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'D','format' => 'raw',
            'value' => function($model){
                    return (empty($model->D)) ? 0 : $model->D;
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'E','format' => 'raw',
            'value' => function($model){
                    return (empty($model->E)) ? 0 : $model->E;
            },
        ], 
 
    ],
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
]) ?>
  </div>
  <div class="panel-footer"></div>
</div>