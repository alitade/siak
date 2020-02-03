<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
Yii::$app->controller->renderPartial('schedule', ['model'=>$model]); ?><br/><br/>

<div class="pull-left">
    <?php
        echo Html::a(" <i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak KPU A4",
        ['mahasiswa/cetak-kpu','kr'=>$kr,'t'=>'uts'],
        ['class'=>' btn btn-success','target'=>'_blank',])
		.' '.Html::a(" <i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak KPU A5",
        ['mahasiswa/cetak-kpu','kr'=>$kr,'t'=>'uts','s'=>5],
        ['class'=>' btn btn-success','target'=>'_blank',])
		;
    ?>
</div>
<br />
<br />
<br />
<?php
/*var_dump($dataProvider);
die();*/
echo 
GridView::widget([
    'dataProvider' => @$dataProvider,
    'filterModel' => false,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>'<i class="fa fa-navicon"></i> Jadwal UTS',
    ],
    'columns' => [
         ['class' => 'kartik\grid\SerialColumn'],
         [
             'header'  => 'Kode',
             'value' => function($data) {
		            return $data['mtk_kode'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Matakuliah',
             'value' => function($data) {
		            return $data['mtk_nama'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Semester',
             'value' => function($data) {
		            return $data['mtk_semester'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jadwal UTS',
             'value' => function($data) {
		            return $data['jdwl_uts'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jam',
             'value' => function($data) {
		            return $data['jdwl_uts'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Ruangan',
             'value' => function($data) {
		            return $data['rg_uts'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Kelas',
             'value' => function($data) {
		            return $data['jdwl_kls'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Dosen',
             'value' => function($data) {
		            return $data['ds_nm'];
	            },
             'format'  => 'raw',
         ],
   
   //      ['class' => 'yii\grid\ActionColumn'],
    ],
]);

	?>