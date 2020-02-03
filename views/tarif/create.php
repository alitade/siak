<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 */

$this->title = 'Tambah Data Tarif';
$this->params['breadcrumbs'][] = ['label' => 'Tarif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
