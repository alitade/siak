<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Registrasi $model
 */

$this->title = 'Create Registrasi';
$this->params['breadcrumbs'][] = ['label' => 'Registrasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasi-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
