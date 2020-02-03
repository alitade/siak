<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Program $model
 */

$this->title = 'Update Program: ' . ' ' . $model->pr_kode;
$this->params['breadcrumbs'][] = ['label' => 'Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pr_kode, 'url' => ['view', 'id' => $model->pr_kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
