<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = 'Update Ruang: ' . ' ' . $model->rg_kode;
$this->params['breadcrumbs'][] = ['label' => 'Ruang', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rg_kode, 'url' => ['view', 'id' => $model->rg_kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ruang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
