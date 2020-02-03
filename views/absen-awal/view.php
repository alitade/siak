<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AbsenAwal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absen Awals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absen-awal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'GKode',
            'jdwl_masuk',
            'jdwl_keluar',
            'tgl',
            'ds_masuk',
            'ds_keluar',
            'tipe',
            'ds_fid',
        ],
    ]) ?>

</div>
