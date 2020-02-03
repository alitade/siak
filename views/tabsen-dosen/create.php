<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\TAbsenDosen $model
 */

$this->title = 'Create Tabsen Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Tabsen Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tabsen-dosen-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
