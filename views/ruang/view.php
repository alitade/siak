<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = $model->rg_kode;
$this->params['breadcrumbs'][] = ['label' => 'Ruang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'rg_kode',
            'rg_nama',
            'kapasitas',
			[
				'attribute'=>'IdGedung',
				'value'=>$model->gedung->Name,
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::GEDUNG(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => 'Pilih Gedung... ',
						'multiple' =>false,							
					],
				],
			],
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->rg_kode],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
