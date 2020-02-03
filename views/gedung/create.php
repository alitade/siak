<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Gedung $model
 */

$this->title = 'Tambah Data Gedung';
$this->params['breadcrumbs'][] = ['label' => 'Gedung', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gedung-create">
    <?= $this->render('_form', [
        'model' => $model,
		'title'=>$this->title,
    ]) ?>

</div>
