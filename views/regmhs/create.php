<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Regmhs $model
 */

$this->title = 'Create Regmhs';
$this->params['breadcrumbs'][] = ['label' => 'Regmhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regmhs-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
