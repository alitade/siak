<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MatkulKrDet $model
 */

$this->title = 'Create Matkul Kr Det';
$this->params['breadcrumbs'][] = ['label' => 'Matkul Kr Dets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-kr-det-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
