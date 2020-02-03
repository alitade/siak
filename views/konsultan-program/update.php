<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\KonsultanProgram $model
 */

$this->title = 'Update Konsultan Program: ' . ' ' . $model->jurusan_id;
$this->params['breadcrumbs'][] = ['label' => 'Konsultan Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jurusan_id, 'url' => ['view', 'jurusan_id' => $model->jurusan_id, 'konsultan_id' => $model->konsultan_id, 'program_id' => $model->program_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="konsultan-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
