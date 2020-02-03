<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Pkrs $model
 */

$this->title = 'Update Pkrs: ' . ' ' . $model->kr_kode;
$this->params['breadcrumbs'][] = ['label' => 'Pkrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kr_kode, 'url' => ['view', 'kr_kode' => $model->kr_kode, 'mhs_nim' => $model->mhs_nim]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pkrs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
