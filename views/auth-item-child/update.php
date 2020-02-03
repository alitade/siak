<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\AuthItemChild $model
 */

$this->title = 'Update Auth Item Child: ' . ' ' . $model->child;
$this->params['breadcrumbs'][] = ['label' => 'Auth Item Children', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->child, 'url' => ['view', 'child' => $model->child, 'parent' => $model->parent]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-item-child-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
