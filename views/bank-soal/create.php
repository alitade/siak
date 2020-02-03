<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BankSoal $model
 */

$this->title = 'Create Bank Soal';
$this->params['breadcrumbs'][] = ['label' => 'Bank Soals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-soal-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
