<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BankSoal $model
 */

$this->title = 'Update Bank Soal: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Soals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-soal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
