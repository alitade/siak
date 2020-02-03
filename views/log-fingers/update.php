<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LogFingers $model
 */

$this->title = 'Update Log Fingers: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Log Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="log-fingers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
