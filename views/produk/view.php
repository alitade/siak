<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Produk $model
 */

$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Produks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
		'model' => $model,
		'condensed'=>false,
		'hover'=>true,
		'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
		'panel'=>[
		'heading'=>'Produk',
		'type'=>DetailView::TYPE_PRIMARY,
	],
	'attributes' => [
		'kode',
		'produk',
	],
	'deleteOptions'=>[
		'url'=>['delete', 'id' => $model->kode],
		'data'=>[
			'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
			'method'=>'post',
		],
	],
	'enableEditMode'=>($model->Lock=='1'?false:true),
]) ?>

<?= $this->render('/produk-harga/_form1', ['model' => $modHrg,])?>


<div style="clear:both"></div>

    <?= 
		$this->render('/produk-harga/grid', [
			'model'=>$model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'hideAdd'=>true,
			'hideProd'=>true,
    	]) 
	?>

