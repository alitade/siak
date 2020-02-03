<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogTransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Transaksis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-transaksi-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Log Transaksi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'ip4',
            'ip6',
            'user_agent',
            // 'tgl',
            // 'ket',
            // 'kode',
            // 'tb',
            // 'pk',
            // 'aktifitas',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
