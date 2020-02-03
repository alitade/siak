<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\transkrip\models\WisudaSearch $searchModel
 */

$this->title = 'Yudisium';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wisuda-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Wisuda', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
				'attribute'=>'jr_id',
				'header'=>'Jurusan',
				'value'=>function($model){
					return $model->mhs->jr->jr_jenjang." ".$model->mhs->jr->jr_nama;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>\app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'Jurusan'],
			],
            'npm',
            'nama',
			[
				'header'=>'No Ijazah',
				'format'=>'raw',
				'value'=>function($model){
					
					return ($model->kode_?
						strtoupper($model->no_urut."/".$model->kode."/".($model->tgl_lulus?date('Y',strtotime($model->tgl_lulus)):" - ") )
					:' - ')
					//."<br />".$model->kode_
					;
				}
			],
            [
				'attribute'=>'tgl_lulus',
				'format'=>[
					'date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? 
					Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']
			], 
			[
				'attribute'=>'predikat',
				'filter'=>[
					'Dengan Pujian'=>'Dengan Pujian',
					'Sangat Memuaskan'=>'Sangat Memuaskan',
					'Memuaskan'=>'Memuaskan',
					'Cumloude / Dengan Pujian'=>'Cumloude / Dengan Pujian',
					' '=>' - ',
				]
				
			],

            [
                'class' => 'kartik\grid\ActionColumn',
				'template'=>
					'
								<li>{view}</li>
								<li>{delete}</li>
								<li>{update}</li>
					',
                'buttons' => [
					'update' => function ($url, $model) {
						return Html::a(
						'<span class="glyphicon glyphicon-pencil"></span> Update',
						Yii::$app->urlManager->createUrl(['/transkrip/wisuda/view','id' => $model->id,'edit'=>'t']),
						['title' => Yii::t('yii', 'Edit'),]);
					},
					
                ],
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				
				
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Data', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
