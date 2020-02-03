<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-view">

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
            'krs_id',
            'krs_stat',
            'ds_fid',
            'ds_fid1',
            'mtk_kode',
            'mtk_nama',
            'jdwl_id',
            'jdwl_hari',
            'jdwl_masuk',
            'jdwl_keluar',
            'tgl',
            'mhs_fid',
            'mhs_masuk',
            'mhs_keluar',
            'mhs_stat',
            'ds_masuk',
            'ds_keluar',
            'ds_stat',
            'ds_get_fid',
            'tgl_ins',
        ],
    ]) ?>

</div>
