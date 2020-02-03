<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Tambah Jadwal';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Kuliah', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-create">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('jdw__form', [
        'model' => $model,
    ]) ?>

</div>
