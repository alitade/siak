<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title =$model->kr->kr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['/kalender/index']];
$this->params['breadcrumbs'][] = $this->title;

$Dkrs = date_create($model->kln_krs);
$Dkrs->modify('+'.(int)$model->kln_krs_lama.' days');
//$Dkrs = date_add($Dkrs, date_interval_create_from_date_string("$model->kln_krs_lama days"));
$Dkrs = date_format($Dkrs, 'Y-m-d');

$Duts = date_create($model->kln_uts);
$Duts->modify('+'.(int)$model->kln_uts_lama.' days');
//$Duts = date_add($Duts, date_interval_create_from_date_string("$model->kln_uts_lama days"));
$Duts = date_format($Duts, 'Y-m-d');

$Duas = date_create($model->kln_uas);
$Duas->modify('+'.(int)$model->kln_uas_lama.' days');
$Duas = date_format($Duas, 'Y-m-d');

/*
$Duas = date_add($Duas, date_interval_create_from_date_string("$model->kln_uas_lama days"));
$Duas = date_format($Duas, 'Y-m-d');
*/
$a='aaa';

?>
<div class="kalender-view">
    <p></p>

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Kalender Akademik : ' . $model->kr->kr_nama,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
			[ 
				'attribute'=>'kr_kode',
				'displayOnly'=>true,
			],
			[ 
				'attribute'=>'jr_id',
				'value'=>$model->jr->jr_jenjang." ".$model->jr->jr_nama,
				'displayOnly'=>true,
			],
			[ 
				'attribute'=>'pr_kode',
				'value'=>$model->pr->pr_nama,
				'displayOnly'=>true,
			],
            [
                'attribute'=>'kln_krs',
				'value'=>Funct::TANGGAL($model->kln_krs)." - ".Funct::TANGGAL($Dkrs),
            ],
            [
                'attribute'=>'kln_masuk',
				'value'=>Funct::TANGGAL($model->kln_masuk),
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
					'displayFormat'=>'php:Y-m-d',
					'saveFormat'=>'php:Y-m-d',
                ]
            ],
            [
                'attribute'=>'kln_uts',
				'value'=>Funct::TANGGAL($model->kln_uts)." - ".Funct::TANGGAL($Duts),
            ],
            [
                'attribute'=>'kln_uas',
				'value'=>Funct::TANGGAL($model->kln_uas)." - ".Funct::TANGGAL($Duas),
            ],
            'kln_sesi',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->kln_id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
