<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\transkrip\models\Pejabat */

$this->title = 'Create Pejabat';
$this->params['breadcrumbs'][] = ['label' => 'Pejabats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pejabat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
