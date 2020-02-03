<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Update Dosen: ' . ' ' . $model->ds_nidn;
$this->params['breadcrumbs'][] = ['label' => 'Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ds_nidn, 'url' => ['view', 'id' => $model->ds_nidn]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dosen-update">

   <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
