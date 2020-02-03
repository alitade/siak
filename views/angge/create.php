<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Angge $model
 */

$this->title = 'Create Angge';
$this->params['breadcrumbs'][] = ['label' => 'Angges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="angge-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
