<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Konsultans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="konsultan-view">
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
            'kode',
            'vendor',
            'email:email',
            'tlp',
            'pic',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->kode],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
