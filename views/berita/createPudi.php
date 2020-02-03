<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Berita */

$this->title = 'Tambah Berita';
$this->params['breadcrumbs'][] = ['label' => 'Kelola Berita', 'url' => ['pudi', 'id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berita-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
