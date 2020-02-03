<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 */

$this->title = $model->mtk_nama;
$this->params['breadcrumbs'][] = ['label' => 'Matakuliah', 'url' => ['mtk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel'=>[
	        'heading'=>'Matakuliah : ' . $model->mtk_nama,
	        'type'=>DetailView::TYPE_PRIMARY,
	    ],
        'attributes' => [
            'mtk_kode',
            'mtk_nama',
            'mtk_sks',
			[
				'attribute'=>'mtk_stat',
				'value'=>($model->mtk_stat==1?'Aktif':'Non Aktif'),
				'type'=>DetailView::INPUT_SWITCH,
				'widgetOptions'=>[
					'pluginOptions' => [
						'onText'=>'Aktif',
						'offText'=>'Non Aktif',
					],
					'value'=>$model->mtk_stat
				],
			
			],
			[
				'attribute'=>'jr_id',
				'value'=>$model->jr->jr_nama,
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			],
            [
            	'attribute'=>'penanggungjawab',
            	'value'=>@$model->dosen->ds_nm,
            ],
			[
				'attribute'=>'mtk_sub',
				'value'=>@Funct::MTK()[@$model->mtk_sub],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::MTK('1',["jr_id"=>$model->jr_id]),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			
			],
            'mtk_semester',
			[
				'attribute'=>'mtk_kat',
				'value'=>@Funct::MTKJNS()[@$model->mtk_kat],
				'type'=>DetailView::INPUT_SELECT2,
				'widgetOptions'=>[
	                'data' => app\models\Funct::MTKJNS(),
					'options' => [
						'fullSpan'=>6,
						'placeholder' => '... ',
						'multiple' =>false,
						'allowClear' => true
					],
				],
			
			],
        ],
        'enableEditMode'=>Funct::acc('/matkul/update'),
    ]) ?>

</div>
