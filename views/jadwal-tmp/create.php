<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\JadwalTmp $model
 */

$this->title = 'Create Jadwal Tmp';
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Tmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-tmp-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
