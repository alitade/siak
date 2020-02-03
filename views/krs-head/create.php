<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\KrsHead $model
 */

$this->title = 'Create Krs Head';
$this->params['breadcrumbs'][] = ['label' => 'Krs Heads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="krs-head-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
