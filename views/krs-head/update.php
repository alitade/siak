<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\KrsHead $model
 */

$this->title = 'Update Krs Head: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Krs Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="krs-head-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
