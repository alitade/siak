<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaksi Fingers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-finger-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transaksi Finger', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'krs_id',
            'krs_stat',
            'ds_fid',
            'ds_fid1',
            // 'mtk_kode',
            // 'mtk_nama',
            // 'jdwl_id',
            // 'jdwl_hari',
            // 'jdwl_masuk',
            // 'jdwl_keluar',
            // 'tgl',
            // 'mhs_fid',
            // 'mhs_masuk',
            // 'mhs_keluar',
            // 'mhs_stat',
            // 'ds_masuk',
            // 'ds_keluar',
            // 'ds_stat',
            // 'ds_get_fid',
            // 'tgl_ins',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
