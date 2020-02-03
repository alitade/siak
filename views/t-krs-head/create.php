<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\TKrsHead $model
 */

$this->title = 'Create Tkrs Head';
$this->params['breadcrumbs'][] = ['label' => 'Tkrs Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tkrs-head-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
