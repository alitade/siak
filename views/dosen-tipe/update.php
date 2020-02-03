<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\DosenTipe $model
 */

$this->title = 'Update Dosen Tipe: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Tipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-tipe-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
