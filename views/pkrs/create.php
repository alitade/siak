<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Pkrs $model
 */

$this->title = 'Create Pkrs';
$this->params['breadcrumbs'][] = ['label' => 'Pkrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pkrs-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
