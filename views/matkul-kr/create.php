<?php
use yii\helpers\Html;
$this->title = 'Tambah Kurikulum';
$this->params['breadcrumbs'][] = ['label' => 'Kurikulum', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-kr-create"><?= $this->render('_form', ['model' => $model,'subAkses'=>$subAkses]) ?></div>
