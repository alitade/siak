<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ujian */

$this->title = 'Create Ujian';
$this->params['breadcrumbs'][] = ['label' => 'Ujians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ujian-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
