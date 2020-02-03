<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenAbsen $model
 */

$this->title = 'Create Dosen Absen';
$this->params['breadcrumbs'][] = ['label' => 'Dosen Absens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-absen-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
