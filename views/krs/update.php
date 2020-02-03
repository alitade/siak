<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 */

$this->title = 'Update Krs: ' . ' ' . $model->krs_id;
$this->params['breadcrumbs'][] = ['label' => 'Krs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->krs_id, 'url' => ['view', 'id' => $model->krs_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="krs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
