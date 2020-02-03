<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Berita #Biro Administrasi Akademik ';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
        'tanggal',
        'judul',
        'kategori',
        
        
        [
            'attribute'=>'id_user',
            'value'=>'idUser.name',
        ],
        
        'status',
        [
            'class' => 'kartik\grid\ActionColumn', 
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $detail) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view-berita', 'id' => $detail['id_berita']], [
                            'title' => Yii::t('app', 'View'),
                    ]);
                },
                'update' => function ($url, $detail) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-baa', 'id' => $detail['id_berita']], [
                            'title' => Yii::t('app', 'Update'),
                    ]);
                },
                'delete' => function ($url, $detail) {
                    return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete-baa', 'id' => $detail['id_berita']], [
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                }
            ],
        ], 
    
];

$export = ExportMenu::widget([
    'dataProvider' => $detail,
    'columns' => $gridColumns,
    'fontAwesome' => true,
    'filename'=>'Data Berita',
    'dropdownOptions' => [
        'label' => 'Export',
        'class' => 'btn btn-default'
    ]
    ])

?>
<div class="berita-index">
<div class="page-header">
    <h3><?= Html::encode($this->title) ?></h3>
</div>
<?= GridView::widget([
    'dataProvider'=>$detail,
    'filterModel'=>$searchModel,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    // set your toolbar
    'beforeHeader'=>[
        [
            'options'=>['class'=>'skip-export'] // remove this row from export
        ]
    ],
    'toolbar'=> [
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['create-baa'],['class'=>'btn btn-success'])
        ]
    ],
    // set export properties
    'export'=>[
        'fontAwesome'=>true
    ],
    // parameters from the demo form
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Berita',
    ]
]); ?>

</div>
