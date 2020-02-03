<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\transkrip\models\Wisuda $model
 */

$this->title = 'Tambah Data Yudisium';
$this->params['breadcrumbs'][] = ['label' => 'Wisudas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wisuda-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
