<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsDet $model
 */

$this->title = 'Create Tkrs Det';
$this->params['breadcrumbs'][] = ['label' => 'Tkrs Dets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tkrs-det-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
