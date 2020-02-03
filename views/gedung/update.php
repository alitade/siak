<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Gedung $model
 */

$this->title = 'Ubah Data Gedung: ' . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Gedung', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gedung-update">
    <?= $this->render('_form', [
        'model' => $model,
		'title'=>$this->title,
    ]) ?>
</div>
