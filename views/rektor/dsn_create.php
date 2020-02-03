<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Dosen $model
 */

$this->title = 'Tambah Dosen Wali';
$this->params['breadcrumbs'][] = ['label' => 'Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-create">
    <div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?= $this->render('dsn__form', [
			'ModDsn' => $ModDsn,
			'ModMhs' => $ModMhs,
    ]) ?>

</div>
