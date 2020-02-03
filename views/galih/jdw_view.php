<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = $model->bn->kln->kr->kr_nama;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-view">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
		    'panel'=>[
		        'heading'=>'Jadwal',
		        'type'=>DetailView::TYPE_PRIMARY,
		    ],
        'attributes' => [
			[
				'group'=>true,
				'label'=>'Info Pengajar',
		        'rowOptions'=>['class'=>'warning'],
			],
            [
                'label'=>'Tahun Akademik',
				'format'=>'raw',
				'value'=>$model->bn->kln->kr->kr_nama,
				'displayOnly'=>true,

			],

            [
                'label'=>'Jurusan',
				'format'=>'raw',
				'value'=>Funct::JURUSAN()[$model->bn->kln->jr_id]." (".Funct::PROGRAM()[$model->bn->kln->pr_kode].") ",
				'displayOnly'=>true,
			],
            [
                'label'=>'Matakuliah',
				'format'=>'raw',
				'value'=>Funct::Mtk()[$model->bn->mtk_kode],
				'displayOnly'=>true,
			],
            [
                'label'=>'Dosen',
				'format'=>'raw',
				'value'=>$model->bn->ds->ds_nm,
				'displayOnly'=>true,
			],
			[
				'group'=>true,
				'label'=>'Info Jadwal',
		        'rowOptions'=>['class'=>'warning']
			],
			
            [
                'attribute'=>'rg_kode',
				'value'=>$model->rg->rg_nama,
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::RUANG(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
				
			],
            [
                'attribute'=>'jdwl_hari',
				'value'=>Funct::HARI()[$model->jdwl_hari],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::HARI(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
            //'semester',
            'jdwl_masuk',
            'jdwl_keluar',
            'jdwl_kls',
			[
				'group'=>true,
				'label'=>'Info Ujian',
		        'rowOptions'=>['class'=>'warning']
			],
            [
                'attribute'=>'jdwl_uts',
				'value'=>substr($model->jdwl_uts,0,19),
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] :'php:Y-m-d H:i:s'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME,
					'displayFormat'=>'php:Y-m-d H:i:s',
					'saveFormat'=>'php:Y-m-d H:i:s',
                ]
            ],
            [
                'attribute'=>'jdwl_uts_out',
				'value'=>$model->jdwl_uts_out,
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'php:Y-m-d H:i:s'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
			[
				'attribute'=>'rg_uts',
				'value'=>app\models\Funct::RUANG()[$model->rg_uts],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::RUANG(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			
			],


            [
                'attribute'=>'jdwl_uas',
				'value'=>$model->jdwl_uas,
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'php:Y-m-d H:i:s'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
            [
                'attribute'=>'jdwl_uas_out',
				'value'=>$model->jdwl_uas_out,
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'php:Y-m-d H:i:s'],
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=> [
                    'class'=>DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATETIME
                ]
            ],
			[
				'attribute'=>'rg_uas',
				'value'=>app\models\Funct::RUANG()[$model->rg_uas],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::RUANG(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			
			],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
