<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\KonsultanProgram $model
 */

$this->title = $model->jurusan_id;
$this->params['breadcrumbs'][] = ['label' => 'Konsultan Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="konsultan-program-view">
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
            'konsultan_id',
            'program_id',
            'jurusan_id',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->jurusan_id],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
