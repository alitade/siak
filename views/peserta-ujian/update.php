<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\PesertaUjian $model
 */

$this->title = 'Update Peserta Ujian: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Peserta Ujians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="peserta-ujian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
