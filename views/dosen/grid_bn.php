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
            class: 'btn btn-sm btn-warning',
            html: '<span class=\"glyphicon glyphicon-refresh\"></span> &nbsp Default',
            action: 'default'
        }
    },
    columns: {
        identifier: [0, 'id'],
        editable: [[4, 'nb_tgs1'],[5, 'nb_tgs2'],[6, 'nb_tgs3'],[7, 'nb_tambahan'],[8, 'nb_quis'],[9, 'nb_uts'],[10, 'nb_uas'],[11, 'B'],[12, 'C'],[13, 'D'],[14, 'E']]
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


<?= GridView::widget([
     
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions'=>['id'=>'Nilai'],
    'rowOptions' => function ($data, $index, $widget, $grid){
		$LOCK=0;
		foreach($data->jdw as $d){if($d->Lock==64){$LOCK=1;}}
		
        if($LOCK>0){
            return ['style' => 'color:#000;font-weight:bold'];
        }else{
            return [];
        }
    },      
    'columns' => [
        [
            //'visible' => 'false',         
            'width' => '5%',
            'attribute' => 'id','format' => 'raw',
            'value' => function($model){
					$id="";
					foreach($model->jdw as $d){
						//$id.=",$d->Lock|$d->jdwl_id";
						if($d->Lock==64){return '0';}
					}
                    return $model->id;
            },
        ], 
		[
			'header'=>'Jurusan',
			'value'=>function($model){
				return $model->jr_nama;
			},
			'group'=>true,  // enable grouping,
			//'groupedRow'=>true,                    // move grouped column to a single grouped row
			'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
			'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
		],			

		[
			'attribute'	=> 'pr_nama',
			'header'=>'Program',
			'group'=>true,  // enable grouping,
		],			
		[
			'header'=>'Matakuliah',
			'value'=>function($model){
				return $model->mtk_kode." : ".$model->mtk_nama;
			} 
		],			
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
			'header'=>'Absensi',
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
                    return (empty($model->B)) ? 0 : number_format($model->B,2);
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'C','format' => 'raw',
            'value' => function($model){
                    return (empty($model->C)) ? 0 : number_format($model->C,2);
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'D','format' => 'raw',
            'value' => function($model){
                    return (empty($model->D)) ? 0 : number_format($model->D,2);
            },
        ], 
        [         
            'width' => '5%',
            'attribute' => 'E','format' => 'raw',
            'value' => function($model){
                    return (empty($model->E)) ? 0 : number_format($model->E,2);
            },
        ], 
 
    ],
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Konfigurasi Bobot Nilai',
    ],
	'toolbar'=> [
		[
			'content'=>($dataProvider?Html::a('<i class="fa fa-list"></i> Daftar Jadwal', ['/dosen/jdwl','Kurikulum[kr_kode]'=>$KR],['class'=>'btn btn-info']):" ")
		],
	]
	
]) ?>

<style type="text/css">
    .container {
    width: 100%;
}
</style>