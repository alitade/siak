<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarifdetail $model
 */

$this->title = 'Create Tarifdetail';
$this->params['breadcrumbs'][] = ['label' => 'Tarifdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarifdetail-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
