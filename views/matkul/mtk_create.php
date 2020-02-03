<?php
use yii\helpers\Html;
$this->title = 'Tambah Matakuliah';
$this->params['breadcrumbs'][] = ['label' => 'Matakuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-create">
	<p></p>
    <?= $this->render('mtk_form', [
        'model' => $model,'subAkses'=>$subAkses
    ]) ?>

</div>
