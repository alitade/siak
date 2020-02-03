<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenPengganti $model
 */

$this->title = 'Update Dosen Pengganti: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Penggantis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-pengganti-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
