<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BiodataTemp $model
 */

$this->title = 'Update Biodata Temp: ' . ' ' . $model->id_;
$this->params['breadcrumbs'][] = ['label' => 'Biodata Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_, 'url' => ['view', 'id' => $model->id_]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="biodata-temp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
