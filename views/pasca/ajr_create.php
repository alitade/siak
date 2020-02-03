<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Krs $model
 */

$this->title = 'Tambah Pengajar';
$this->params['breadcrumbs'][] = ['label' => 'Pegajar', 'url' => ['ajr']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="krs-create">
<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('ajr__form', [
        'model' => $model,
		'J'=>$J
    ]) ?>

</div>
