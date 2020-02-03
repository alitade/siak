<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = 'Ubah Ruang: ' . ' ' . $model->rg_nama;
$this->params['breadcrumbs'][] = ['label' => 'Ruangan', 'url' => ['rg']];
$this->params['breadcrumbs'][] = ['label' => $model->rg_nama, 'url' => ['view', 'id' => $model->rg_kode]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="ruang-update">

    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('rg__form', [
        'model' => $model,
    ]) ?>

</div>
