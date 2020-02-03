<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LogFingers $model
 */

$this->title = 'Create Log Fingers';
$this->params['breadcrumbs'][] = ['label' => 'Log Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-fingers-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
