<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LAbsenDosen $model
 */

$this->title = 'Create Labsen Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Labsen Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="labsen-dosen-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
