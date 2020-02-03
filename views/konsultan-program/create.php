<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\KonsultanProgram $model
 */

$this->title = 'Create Konsultan Program';
$this->params['breadcrumbs'][] = ['label' => 'Konsultan Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="konsultan-program-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
