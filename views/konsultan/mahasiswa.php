<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use app\models\Funct;
use kartik\tabs\TabsX;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = "Konsultan ".$model->vendor;
$this->params['breadcrumbs'][] = ['label' => 'Konsultan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$items = [
    [
        'label'=>'<i class="fa fa-users"></i> Mahasiswa',
        'content'=>'Mahasiswa',
        //'active'=>$ActiveForm,
        #'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
    [
        'label'=>'<i class="fa fa-list"></i> Program ',
        'content'=>
            /*
            GridView::widget([
                'dataProvider' => $dataPr,
                'filterModel' => $searchPr,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'nama_program',

                ]
            ])
            */
            $dataProgram

                ,
        //'active'=>$ActiveForm,
        #'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
    [
        'label'=>'<i class="fa fa-list"></i> Tarif ',
        'content'=>'Mahasiswa',
        //'active'=>$ActiveForm,
        #'visible'=>Funct::acc('/matkul-kr/add-matkul')
    ],
];


?>

<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Konsultan <?= $model->vendor." ($model->kode)" ?></span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">
            PIC: -  <?= "" ?>, Email:<?= ($model->email?:"-") ?>, Tlp:<?= ($model->tlp?:"-") ?>
        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?=
        TabsX::widget([
            'enableStickyTabs'=>true,
            'height'=>TabsX::SIZE_LARGE,
            'items'=>$items,
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false,
            'bordered'=>true,
            //'sideways'=>TabsX::POS_LEFT,
        ]);
        ?>
    </div>
</div>
