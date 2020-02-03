<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Create Kalender';
$this->params['breadcrumbs'][] = ['label' => 'Kalenders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kalender-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
