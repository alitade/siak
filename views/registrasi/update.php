<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Registrasi $model
 */

$this->title = 'Update Registrasi: ' . ' ' . $model->No_Registrasi;
$this->params['breadcrumbs'][] = ['label' => 'Registrasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->No_Registrasi, 'url' => ['view', 'id' => $model->No_Registrasi]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="registrasi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
