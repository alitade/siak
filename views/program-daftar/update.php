<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ProgramDaftar $model
 */

$this->title = 'Update Program Daftar: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Program Daftars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="program-daftar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
