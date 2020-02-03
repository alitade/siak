<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\ProdukSearch $searchModel
 */

$this->title = 'Produk';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Produk', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kode_produk',
            'harga',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
					'update' => function ($url, $model) {
						if($model->Lock==1){return false;}
						return Html::a('<span class="fa fa-pencil"></span>', Yii::$app->urlManager->createUrl(['produk/view','id' => $model->kode_produk,'edit'=>'t']),
							['title' => Yii::t('yii', 'Edit'),]);
						},
					'view' => function ($url, $model) {
						return Html::a('<span class="fa fa-eye"></span>', Yii::$app->urlManager->createUrl(['produk/view','id' => $model->kode_produk,'edit'=>'t']),
							['title' => Yii::t('yii', 'Edit'),]);
						},
					'delete' => function ($url, $model) {
						if($model->Lock==1){return false;}
						return Html::a('<span class="fa fa-trash"></span>', Yii::$app->urlManager->createUrl(['produk/view','id' => $model->kode_produk,'edit'=>'t']),
							['title' => Yii::t('yii', 'Edit'),]);
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
