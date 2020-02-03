<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Beasiswalist $model
 */

$this->title = 'Create Beasiswalist';
$this->params['breadcrumbs'][] = ['label' => 'Beasiswalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beasiswalist-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
