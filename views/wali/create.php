<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Wali $model
 */

$this->title = 'Create Wali';
$this->params['breadcrumbs'][] = ['label' => 'Walis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wali-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
