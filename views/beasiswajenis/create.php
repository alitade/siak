<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswajenis $model
 */

$this->title = 'Create Beasiswajenis';
$this->params['breadcrumbs'][] = ['label' => 'Beasiswajenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beasiswajenis-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
