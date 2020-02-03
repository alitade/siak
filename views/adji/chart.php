<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use app\models\Funct;
use kartik\select2\Select2;
use app\models\Jurusan;
use app\models\Mahasiswa;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$this->title = 'Grafik Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
	
<div class="panel panel-primary">
	<div class="panel-body">	
		<div class="col-md-6">
			<?php  
				$tmpt=Jurusan::find()->all();
			    $listData=ArrayHelper::map($tmpt, 'jr_id', 'jr_nama');
			    echo $form->field($model, 'jurusan')->widget(Select2::classname(), [
			        'data' => $listData,'options' => ['placeholder' => 'Pilih Jurusan ..'],'pluginOptions' => ['allowClear' => true],
				]);
			?>

			<?php  
				$data = array();
			    $year = date('Y');
			    for ($i = $year - 10; $i < $year + 10; $i++) {
			      $data[$i] = $i;
			    }
			    echo $form->field($model, 'angkatan')->widget(Select2::classname(), [
			        'data' => $data,'options' => ['placeholder' => 'Pilih Tahun ..'],'pluginOptions' => ['allowClear' => true],
				]);
			?>
		</div>
	</div>
</div>

<div class="well">
	<?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-search"></i> Cari' : '<i class="glyphicon glyphicon-edit"></i> Simpan Perubahan', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>


<?php
$label=array();
$nilai=array();
	
	
	foreach ($dataProvider->getModels() as $i=>$ii) {
		$label[$i]=$ii['status_mhs']."/".$ii['angkatan']."/".$ii['total'];
		$nilai[$i]=doubleval($ii['total']);
	}

	echo Highcharts::widget([
	'scripts' => [
	   'themes/sand-signika',
	   'highcharts-more',  
	   'modules/exporting', // adds Exporting button/menu to chart
	],
   'options' => [
      'title' => ['text' => 'Total Mahasiswa: '],
      'xAxis' => [
         'categories' => [$label]
      ],
      'yAxis' => [
         'title' => ['text' => 'Total Mahasiswa']
      ],
      'series' => [
         [
            'type' => 'column',
            'name' => ['Aktif'],
            'data' => $nilai
        ],
      ]
   ]
]);
?>