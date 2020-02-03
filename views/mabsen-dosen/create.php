<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenDosen $model
 */

$this->title = 'Create Mabsen Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Mabsen Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mabsen-dosen-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
