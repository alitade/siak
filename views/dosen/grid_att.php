<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use app\models\JadwalDosen;
use kartik\select2\Select2;
use kartik\editable\Editable;
?>


<?= GridView::widget([
    'dataProvider'=> $dataProvider,
    'tableOptions'=>['id'=>'Attendance'],
    'columns' =>  $columns
        /*'id',
        'jdwl_id', 
        'mhs_nim', 
        [     
            'width' => '5%',
            'attribute' => 'Sesi01','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi01'],1);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi02','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi02'],2);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi03','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi03'],3);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi04','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi04'],4);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi05','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi05'],5);
            },
        ],        
        [     
            'width' => '5%',
            'attribute' => 'Sesi06','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi06'],6);
            },
        ],        
        [     
            'width' => '5%',
            'attribute' => 'Sesi07','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi07'],7);
            },
        ],        
        [     
            'width' => '5%',
            'attribute' => 'Sesi08','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi08'],8);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi09','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi09'],9);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi10','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi10'],10);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi11','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi11'],11);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi12','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi12'],12);
            },
        ],
        [     
            'width' => '5%',
            'attribute' => 'Sesi13','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi13'],13);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi14','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi14'],14);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi15','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi15'],15);
            },
        ], 
        [     
            'width' => '5%',
            'attribute' => 'Sesi16','format' => 'raw',
            'value' => function($model){
                        return JadwalDosen::formatAttendance($model,$model['Sesi16'],16);
            },
        ], */
    ,
    'responsive'=>true,
    'hover'=>true,
    'export' => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Input Absensi Mahasiswa',
    ]
]) ?>
