<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\MAbsenMhs $model
 */

$this->title = 'Create Mabsen Mhs';
$this->params['breadcrumbs'][] = ['label' => 'Mabsen Mhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mabsen-mhs-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
