<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Berita */

$this->title = 'Update Berita: ' . ' ' . $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Kelola Berita', 'url' => ['pudi','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = ['label' => $model->judul, 'url' => ['view-pudi', 'id' => $model->id_berita]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="berita-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
