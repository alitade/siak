<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AbsenKhusus $model
 */

$this->title = 'Create Absen Khusus';
$this->params['breadcrumbs'][] = ['label' => 'Absen Khususes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absen-khusus-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
