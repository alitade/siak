<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use app\models\Funct;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Berita Pending';
$this->params['breadcrumbs'][] = $this->title;

$data =ArrayHelper::map(funct::getStatus() , 'id', 'nama');

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
        'judul',
        'kategori',
        //'status',
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute'=>'status',
            'label' => 'Status',
            'editableOptions'=> [
                'header' => 'Status',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                'data'=>$data, // any list of values
                'options' => ['class'=>'form-control', 'prompt'=>'Pilih Status...'],]
        ],
        [
            'attribute'=>'id_user',
            'value'=>'idUser.name',
        ],
        'tanggal',
    [       'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {delete}',
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
            Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Kembali', ['index'],['class'=>'btn btn-warning']). ' '.
            Html::a('<i class="glyphicon glyphicon-ok"></i> Publish', ['publish'],['class'=>'btn btn-info']),
        ],
    ],
    // set export properties
    'export'=>[
        'fontAwesome'=>true
    ],
    // parameters from the demo form
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> History Berita yang di Pending',
    ]
]); ?>

</div>
