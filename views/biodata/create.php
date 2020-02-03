<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Biodata $model
 */

$this->title = 'Create Biodata';
$this->params['breadcrumbs'][] = ['label' => 'Biodatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biodata-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
