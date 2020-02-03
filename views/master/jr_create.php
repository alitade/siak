<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jurusan $model
 */

$this->title = 'Tambah Jurusan';
$this->params['breadcrumbs'][] = ['label' => 'Jurusan', 'url' => ['jr']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jurusan-create">
    <?= $this->render('jr__form', [
        'model' => $model,
		'title'=>$this->title,
    ]) ?>
</div>
