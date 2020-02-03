<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Fakultas $model
 */

$this->title = 'Create Fakultas';
$this->params['breadcrumbs'][] = ['label' => 'Fakultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fakultas-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
