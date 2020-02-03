<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AbsenAwal */

$this->title = 'Create Absen Awal';
$this->params['breadcrumbs'][] = ['label' => 'Absen Awals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absen-awal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
