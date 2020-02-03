<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Kehadiran Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=> $model,
	'columns'=>[
		'thn'
	]
    //'filterModel' => $searchModel,
    //'columns' => $gridColumns,
]);
?>
