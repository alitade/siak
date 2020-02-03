<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 */

$this->title = 'Create Krs';
$this->params['breadcrumbs'][] = ['label' => 'Krs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="krs-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
