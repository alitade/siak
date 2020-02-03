<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = 'Update Konsultan: ' . ' ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Konsultans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="konsultan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
