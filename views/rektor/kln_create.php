<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kalender $model
 */

$this->title = 'Tambah Kalender Akademik';
$this->params['breadcrumbs'][] = ['label' => 'Kalender Akademik', 'url' => ['akademik/kln']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kalender-create">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('kln__form', [
        'model' => $model,
    ]) ?>

</div>
