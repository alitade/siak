<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = 'Create Ruang';
$this->params['breadcrumbs'][] = ['label' => 'Ruang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
