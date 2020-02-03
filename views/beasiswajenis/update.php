<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswajenis $model
 */

$this->title = 'Update Beasiswajenis: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Beasiswajenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="beasiswajenis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
