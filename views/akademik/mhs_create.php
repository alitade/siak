<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Mahasiswa $model
 */

$this->title = 'Tambah Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['mhs']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mahasiswa-create">
   <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('mhs__form', [
        'model' => $model,
    ]) ?>

</div>
