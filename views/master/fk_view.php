<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Fakultas $model
 */

$this->title = $model->fk_nama;
$this->params['breadcrumbs'][] = ['label' => 'Fakultas', 'url' => ['fk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fakultas-view">
<?= DetailView::widget([
    'model'=>$model,
    'condensed'=>true,
    'hover'=>true,
    'enableEditMode'=>false,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Fakultas : ' . $model->fk_nama,
        'type'=>DetailView::TYPE_PRIMARY,
    ],
    'attributes'=>[
        'fk_id',
        'fk_nama',
    ],
    'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->fk_id],
            'data'=>[
            'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
            'method'=>'post',
            ],
        ],
]); ?>

</div>
