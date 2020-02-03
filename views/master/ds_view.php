<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = $model->ds_nm;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Dosen', 'url' => ['ds']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Dosen : ' . $model->ds_nm,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
        'attributes' => [
            'ds_nidn',
            'ds_nm',
            [
                'attribute'=>'id_tipe',
                'label'=>'Tipe',
                'value'=>$model->tipe->tipe,

            ],
            'ds_user',
            'ds_email:email',
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
