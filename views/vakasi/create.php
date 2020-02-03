<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Vakasi $model
 */

$this->title = 'Create Vakasi';
$this->params['breadcrumbs'][] = ['label' => 'Vakasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vakasi-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
