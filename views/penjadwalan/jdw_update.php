<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Ubah Jadwal: ' . ' ' . $model->jdwl_id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwals', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = ['label' => $model->jdwl_id, 'url' => ['view', 'id' => $model->jdwl_id]];
$this->params['breadcrumbs'][] = 'Ubah';
$q="select dbo.validasiJadwal(jdwl_id) t from tbl_jadwal where jdwl_id='$model->jdwl_id'";
$q=Yii::$app->db->createCommand($q)->queryOne();
if(
//$q['t']>0
false
){
	echo "<div class='alert alert-success'>Jadwal sudah memiliki Peserta</div>";
}else{
?>
<div class="jadwal-update">
	<div class="page-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <?=
"";	?>
    <?= $this->render('jdw__form', [
        'model' => $model,
        'q' => $q,
    ]) ?>
</div>
<?php
}
?>
