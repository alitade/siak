<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\Matkul $model
 */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Matakuliah', 'url' => ['mtk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-view">
	<div class="page-header">
        <h3><?= Html::encode(" Matakuliah: ".$this->title) ?></h3>
    </div>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel'=>[
	        'heading'=>'Matakuliah : ' . $model->nama,
	        'type'=>DetailView::TYPE_PRIMARY,
	    ],
        'attributes' => [
            'kode',
            'nama',
            'sks',
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
