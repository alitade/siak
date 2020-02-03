<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Wali $model
 */

$this->title = 'Update Wali: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Walis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wali-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
