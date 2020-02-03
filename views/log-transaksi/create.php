<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LogTransaksi */

$this->title = 'Create Log Transaksi';
$this->params['breadcrumbs'][] = ['label' => 'Log Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-transaksi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
