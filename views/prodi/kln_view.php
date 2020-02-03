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
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['kln']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="kalender-view">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
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
				'attribute'=>'kln_id',
				'displayOnly'=>true,
			],
			[ 
				'attribute'=>'kr_kode',
				'displayOnly'=>true,
			],
			[ 
				'attribute'=>'jr_id',
				'value'=>$model->jr->jr_nama,
				'displayOnly'=>true,
			],
			[ 
				'attribute'=>'pr_kode',
				'value'=>$model->pr->pr_nama,
				'displayOnly'=>true,
			],
            [
                'attribute'=>'kln_krs',
				'value'=>Funct::TANGGAL($model->kln_krs),
                'type'=>DetailView::INPUT_WIDGET,
	               'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
					'displayFormat'=>'php:Y-m-d',
					'saveFormat'=>'php:Y-m-d',
                ]
            ],
            [
                'attribute'=>'kln_masuk',
                'type'=>DetailView::INPUT_WIDGET,
				'value'=>Funct::TANGGAL($model->kln_masuk),
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
					'displayFormat'=>'php:Y-m-d',
					'saveFormat'=>'php:Y-m-d',
                ]
            ],
            [
                'attribute'=>'kln_uts',
                'type'=>DetailView::INPUT_WIDGET,
				'value'=>Funct::TANGGAL($model->kln_uts),
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
					'displayFormat'=>'php:Y-m-d',
					'saveFormat'=>'php:Y-m-d',
                ]
            ],
            [
                'attribute'=>'kln_uas',
                'type'=>DetailView::INPUT_WIDGET,
				'value'=>Funct::TANGGAL($model->kln_uas),
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
					'displayFormat'=>'php:Y-m-d',
					'saveFormat'=>'php:Y-m-d',
                ]
            ],
            'kln_krs_lama',
            'kln_uts_lama',
            'kln_uas_lama',
            'kln_stat',
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
