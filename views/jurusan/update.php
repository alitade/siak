<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 */

$this->title = 'Update Jurusan: ' . ' ' . $model->jr_id;
$this->params['breadcrumbs'][] = ['label' => 'Jurusans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jr_id, 'url' => ['view', 'id' => $model->jr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jurusan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
