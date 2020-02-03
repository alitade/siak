<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = $model->ds_nidn;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['dsn']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-view">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
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
            'ds_nidn',
            'ds_user',
            'ds_pass',
            'ds_pass_kode',
            'ds_nm',
            'ds_tipe',
            'ds_kat',
            'ds_email:email',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->ds_nidn],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
