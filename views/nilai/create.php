<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Nilai $model
 */

$this->title = 'Create Nilai';
$this->params['breadcrumbs'][] = ['label' => 'Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nilai-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
