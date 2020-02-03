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
			'format'=>'raw',
			'value'=>function($model){
				return $model['mhs_nim'];
			}
			
        ], 
        [     
            'width' => 'auto',
            'label' => 'Mahasiswa',
            'attribute' => 'Nama',
			'format'=>'raw',
			'value'=>function ($model){
				$ket = $model[Nama];
				if(is_array(Funct::ATT($model[krs_id]))){
					$ket.="<div  style='font-size:12px;color:red;font-weight:bold'>*".implode(", ",Funct::ATT($model[krs_id]))."</div>";
				}	
				return $ket;
			}
			
			
        ], 
        [
             'label'  => 'Absensi',
             'value' => function($model) {
                    return Funct::cekAbsen($model["krs_id"]);//$data["krs_grade"];
                },
             'format'  => 'raw',
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
                        number_format($model['total'],2)  : $model['krs_tot'] ;
            }
        ],
        [
            'label' =>'Grade',
            'value' => function($model) use($BN){

				if(is_array(Funct::ATT($model[krs_id]))){return '-';}	
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
?>
<div class="panel panel-primary">
    <div class="panel-heading">
    <?=  
		@$BN['ds_nm']."<br />"
		.\app\models\Funct::Hari()[$BN[jdwl_hari]]." $BN[jdwl_masuk] - $BN[jdwl_keluar] : "
		.@$BN['mtk_kode'].' '.@$BN['mtk_nama']." ( $BN[jdwl_kls] ) "
	?>
    	
    </div>
  <div class="panel-body">

<?= GridView::widget([     
    'dataProvider'=> $dataProvider,
    'tableOptions'=>['id'=>'InputNilai'],
    'columns' => $Columns,
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
]) ?>     
  </div>
  <div class="panel-footer"></div>
</div>