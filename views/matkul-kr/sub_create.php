<?php

use yii\helpers\Html;


$this->title = 'Tambah Sub Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Sub Kurikulum', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p></p>
<div class="matkul-kr-create">
    <?= $this->render('_sub_form', [
        'model' => $model,
        'Parent'=>$Parent,
    ]) ?>

</div>
