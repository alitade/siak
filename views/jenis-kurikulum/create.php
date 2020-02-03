<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JenisKurikulum */

$this->title = 'Create Jenis Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Kurikulums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-kurikulum-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
