<?php

use kartik\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->ds_nm;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'enableEditMode'=>false,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Dosen : ' . $model->ds_nm,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes' => [
            'ds_nidn',
            'ds_user',
            'ds_nm',
        ],
    ]) ?>

</div>
