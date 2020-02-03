<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Vakasi $model
 */

$this->title = 'Update Vakasi: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vakasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vakasi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
