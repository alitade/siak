<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenMaxsks $model
 */

$this->title = 'Create Dosen Maxsks';
$this->params['breadcrumbs'][] = ['label' => 'Dosen Maxsks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-maxsks-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
