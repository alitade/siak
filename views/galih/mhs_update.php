<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = 'Ubah Mahasiswa: ' . ' ' . $model->mhs->people->Nama;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = ['label' => $model->mhs->people->Nama, 'url' => ['view', 'id' => $model->mhs_nim]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="mahasiswa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('mhs__form', [
        'model' => $model,
    ]) ?>

</div>
