<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\JadwalTmp $model
 */

$this->title = 'Update Jadwal Tmp: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Tmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jadwal-tmp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
