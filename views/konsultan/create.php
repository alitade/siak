<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = 'Create Konsultan';
$this->params['breadcrumbs'][] = ['label' => 'Konsultans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="konsultan-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
