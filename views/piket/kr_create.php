<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kurikulum $model
 */

$this->title = 'Create Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Kurikulums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kurikulum-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('kr__form', [
        'model' => $model,
    ]) ?>

</div>
