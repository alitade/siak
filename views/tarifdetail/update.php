<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarifdetail $model
 */

$this->title = 'Update Tarifdetail: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tarifdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tarifdetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
