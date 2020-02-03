<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AbsenAwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Absen Awals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absen-awal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Absen Awal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'GKode',
            'jdwl_masuk',
            'jdwl_keluar',
            'tgl',
            // 'ds_masuk',
            // 'ds_keluar',
            // 'tipe',
            // 'ds_fid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
