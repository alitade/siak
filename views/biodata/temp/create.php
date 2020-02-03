<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BiodataTemp $model
 */

$this->title = 'Create Biodata Temp';
$this->params['breadcrumbs'][] = ['label' => 'Biodata Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biodata-temp-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
