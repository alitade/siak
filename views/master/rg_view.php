<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = $model->rg_nama;
$this->params['breadcrumbs'][] = ['label' => 'Ruangan', 'url' => ['rg']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-view">
<div class="page-header">
        <h3><?= Html::encode('Ruangan: '.$this->title) ?></h3>
    </div>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel'=>[
        'heading'=>'Ruangan : ' . $model->rg_nama,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
            'rg_kode',
            'rg_nama',
            'kapasitas',
			[
				'attribute'=>'IdGedung',
				'value'=>@$model->gedung->Name,
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
        'enableEditMode'=>false,
    ]) ?>
</div>