<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AbsenKhusus $model
 */

$this->title = 'Update Absen Khusus: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absen Khususes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absen-khusus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
