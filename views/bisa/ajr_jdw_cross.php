<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use app\models\Krs;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\JadwalSearch $searchModel
 */

$this->title = 'Jadwal Kuliah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<h1>Cross Jadwal</h1>
<h3>Mahasiswa yang berlebih : <?php echo $lebih;?></h4>
<?php
echo " Tahun 	".$ModBn->kln->kr_kode."<br />"
	." Jurusan 	".$ModBn->kln->jr->jr_id."<br />"
	." Program 	".$ModBn->kln->pr->pr_kode."<br />"
	." dosen 	".$ModBn->ds->ds_nm."<br />"
	." Mtk 		".$ModBn->mtk_kode.' '.$ModBn->mtk->mtk_nama."<br />"

;
?>

<fieldset>
	<legend> Input Jadwal Perpindahan</legend>

	
  	<?= 
  		$this->render('../akademik/schedule__form', [
        'model' => $model2,
    ]) ?>
	
</fieldset>

</div>
