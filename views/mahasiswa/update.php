<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = 'Update Mahasiswa: ' . ' ' . $model->mhs_nim;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mhs_nim, 'url' => ['view', 'id' => $model->mhs_nim]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mahasiswa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
