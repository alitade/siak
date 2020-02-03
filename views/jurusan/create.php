<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 */

$this->title = 'Create Jurusan';
$this->params['breadcrumbs'][] = ['label' => 'Jurusans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jurusan-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
