<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


$this->title = 'Produk';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-index">
	<fieldset>
    	<legend> Tambah Data </legend>
	<?= $this->render('_form', ['model' => $model,]) ?>
    </fieldset>

    <?php 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kode',
            'produk',
			[
				'attribute'=>'hrg.harga',
				'value'=>function($model){
					return 'Rp.'.number_format($model->hrg->harga);
				},
				'format'=>'raw',
			],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
					'update' => function ($url, $model) {
						if($model->Lock==1){return false;}
						return Html::a('<span class="fa fa-pencil"></span>', Yii::$app->urlManager->createUrl(['produk/view','id' => $model->kode,'edit'=>'t']),
							['title' => Yii::t('yii', 'Edit'),]);
						},
					'view' => function ($url, $model) {
						return Html::a('<span class="fa fa-eye"></span>', Yii::$app->urlManager->createUrl(['produk/view','id' => $model->kode]),
							['title' => Yii::t('yii', 'View'),]);
						},
					'delete' => function ($url, $model) {
						if($model->Lock==1){return false;}
						return Html::a(
							'<span class="fa fa-trash"></span>', 
							Yii::$app->urlManager->createUrl(['produk/delete','id' => $model->kode,'edit'=>'t']),
							[
								'title' => Yii::t('yii', 'Delete'),
								'data' => [
									'confirm' => "Are you sure you want to delete profile?",
									'method' => 'post',
								],							
							]
							);
						},
	
				],
            ],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'',
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); 
	?>

</div>
