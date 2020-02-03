<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Update Kalender: ' . ' ' . $model->kln_id;
$this->params['breadcrumbs'][] = ['label' => 'Kalenders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kln_id, 'url' => ['view', 'id' => $model->kln_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kalender-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
