<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ProgramDaftar $model
 */

$this->title = 'Create Program Daftar';
$this->params['breadcrumbs'][] = ['label' => 'Program Daftars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-daftar-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
