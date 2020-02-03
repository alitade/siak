<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\Mata Kuliahearch $searchModel
 */

$this->title = 'Group Matakuliah';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="matkul-index">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?php 
	Pjax::begin(['enablePushState'=>false]);
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'toolbar'=> [
	        ['content'=>
	            Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah',['gmtk-add'],['class'=>'btn btn-success']).''.
                Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF ',Url::to().'&c=1',['class'=>'btn btn-info','target'=>'_blank'])
	        ],
	        '{toggleData}',
	    ],

        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
			
            'kode',
            'nama',
            'sks',
			[
				'header'=>"&sum;Matkul.",
				'format'=>'raw',
				'value'=>function($model){
					return $model->total;	
				}
			],
        ],
        'responsive'=>true,
        'hover'=>true,
        'floatHeader'=>true,
        'condensed'=>true,
        'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> Matakuliah',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['mtk'], ['class' => 'btn btn-info']),
    	]
    ]);Pjax::end(); ?>
</div>