<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\BobotNilai $model
 */

$this->title = 'Create Bobot Nilai';
$this->params['breadcrumbs'][] = ['label' => 'Bobot Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bobot-nilai-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
