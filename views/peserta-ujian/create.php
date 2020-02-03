<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\PesertaUjian $model
 */

$this->title = 'Create Peserta Ujian';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Ujians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peserta-ujian-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
