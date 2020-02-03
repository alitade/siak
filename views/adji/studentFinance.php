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
$this->title = 'Grafik Keuangan';
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
              'data' => Funct::KalenderKRS(),'options' => ['placeholder' => 'Pilih Kurikulum ..'],'pluginOptions' => ['allowClear' => true],
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
  
  /*var_dump($dataProvider);
  die();*/
  foreach ($dataProvider->getModels() as $i=>$ii) {
   /* var_dump($ii);
    die();*/
    $label[$i]=$ii['kode']."/".$ii['jurusan'];//." ".$ii['status'];
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

/*$label=array();
$nilai=array();

foreach($dataProvider as $i=>$ii)
{
	$label[$i]=@$ii['kode']."/".@$ii['jurusan']." ".@$ii['status'];
	$nilai[$i]=doubleval(@$ii['total']);
	//$name
	}

$this->widget('application.extensions.highcharts.highcharts.HighchartsWidget', array(
'scripts' => array(
      'highcharts-more',   // enables supplementary chart types (gauge, arearange, columnrange, etc.)
      'modules/exporting', // adds Exporting button/menu to chart
      'themes/sand-signika'        // applies global 'grid' theme to all charts
    ),
	
   'options'=>array(
     'chart'=> array('defaultSeriesType'=>'pie','height' => 800,'plotBackgroundColor' => '#ffffff',),
      'title' => array('text' => ' '),
      'legend'=>array('enabled'=>false),
      'xAxis'=>array('categories'=>$label,
			'title'=>array('text'=>''),),
	
	  'yAxis'=> array(
            'min'=> 0,
            'title'=> array(
            'text'=>'Total Mahasiswa'
            ),
        ),
	'colors'=>array('#0563FE', '#6AC36A', '#FFD148', '#FF2F2F'),
      'series' => array(
         array(
		 'type'=>'column',
		 'colorByPoint' => true,
		 'name' => 'Progess',
		 'data' => $nilai
		 ),

      ),

'htmlOptions' => array(
'width'=> '800px',
'heght' => '1750px'
),	  
       
      'tooltip' => array('formatter' => 'js:function(){ return "<b>"+this.point.name+"</b> :"+this.y; }'),
      'tooltip' => array(
		'formatter' => 'js:function() {return "<b>"+ this.x +"</b><br/>"+"Jumlah : "+ this.y; }'
      ),
	  

      'credits'=>array('enabled'=>false),
   )
));*/

?>
