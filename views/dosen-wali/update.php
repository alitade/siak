<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenWali $model
 */

$this->title = 'Update Dosen Wali: ' . ' ' . $model->ds_id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Walis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ds_id, 'url' => ['view', 'ds_id' => $model->ds_id, 'jr_id' => $model->jr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-wali-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
