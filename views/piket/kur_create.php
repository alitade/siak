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
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
