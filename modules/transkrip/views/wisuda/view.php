<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Wisuda $model
 */

$this->title = $model->npm;
$this->params['breadcrumbs'][] = ['label' => 'Yudisium', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wisuda-view">
    <?= DetailView::widget([
            'model' => $ModMhs,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'mhs_nim',
			
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
			'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
			'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>
	


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
			[
				'attribute'=>'jr_id',
				'format'=>'raw',
				//'type'=>DetailView::INPUT_STATIC,
				'displayOnly'=>true,
				'value'=>app\models\Funct::JURUSAN()[$model->jr_id]
			],
			[
				'attribute'=>'npm',
				'format'=>'raw',
				'displayOnly'=>true,
			],
			[
				'attribute'=>'nama',
				'format'=>'raw',
				'displayOnly'=>true,
			],
            'skripsi_indo:ntext',
            'skripsi_end:ntext',
            'no_urut',
            'kode',
            [
                'attribute'=>'tgl_lulus',
                'format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE
                ]
            ],
            'kode_',
            'predikat',
            'nilai',
            'pejabat1',
            'pejabat2',
        ],
        'enableEditMode'=>true,
		'buttons1'=>'{update}'
    ]) ?>

</div>
