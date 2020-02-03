<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fakultas */

$this->title = 'Tambah Fakultas';
$this->params['breadcrumbs'][] = ['label' => 'Fakultas', 'url' => ['fk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fakultas-create">
    <?= $this->render('fk__form', [
        'model' => $model,
		'title'=>$this->title,
    ]) ?>

</div>
