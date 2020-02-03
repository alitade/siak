<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Wali $model
 */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Walis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wali-view">
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
            'Id',
            'JrId',
            'DsId',
            'KrKd',
            'Status',
            'RStat',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->Id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
