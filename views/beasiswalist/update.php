<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswalist $model
 */

$this->title = 'Update Beasiswalist: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Beasiswalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="beasiswalist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
