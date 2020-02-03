<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenTipe $model
 */

$this->title = 'Create Dosen Tipe';
$this->params['breadcrumbs'][] = ['label' => 'Dosen Tipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-tipe-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
