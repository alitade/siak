<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JenisKurikulumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Kurikulums';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-kurikulum-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jenis Kurikulum', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Id',
            'Nama',
            'Rstat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
