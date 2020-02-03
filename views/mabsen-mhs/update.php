<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenMhs $model
 */

$this->title = 'Update Mabsen Mhs: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mabsen Mhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mabsen-mhs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
