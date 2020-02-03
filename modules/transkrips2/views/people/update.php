<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\People $model
 */

$this->title = 'Update People: ' . ' ' . $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Peoples', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->No_Registrasi, 'url' => ['view', 'id' => $model->No_Registrasi]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="people-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
