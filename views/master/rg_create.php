<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Ruang $model
 */

$this->title = 'Tambah Ruangan';
$this->params['breadcrumbs'][] = ['label' => 'Ruangan', 'url' => ['rg']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-create">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('rg__form', [
        'model' => $model,
    ]) ?>

</div>
