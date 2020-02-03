<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulJenis $model
 */

$this->title = 'Create Matkul Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Matkul Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-jenis-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
