<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AbsenAwal */

$this->title = 'Update Absen Awal: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absen Awals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absen-awal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
