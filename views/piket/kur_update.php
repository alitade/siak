<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Kurikulum $model
 */

$this->title = 'Update Kurikulum: ' . ' ' . $model->kr_kode;
$this->params['breadcrumbs'][] = ['label' => 'Kurikulums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kr_kode, 'url' => ['view', 'id' => $model->kr_kode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kurikulum-update">

    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
