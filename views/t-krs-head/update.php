<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsHead $model
 */

$this->title = 'Update Tkrs Head: ' . ' ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Tkrs Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tkrs-head-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
