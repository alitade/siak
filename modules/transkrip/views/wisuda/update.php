<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Wisuda $model
 */

$this->title = 'Update Wisuda: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wisudas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wisuda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
