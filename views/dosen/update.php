<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Update Dosen: ' . ' ' . $model->ds_id;
$this->params['breadcrumbs'][] = ['label' => 'Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ds_id, 'url' => ['view', 'id' => $model->ds_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
