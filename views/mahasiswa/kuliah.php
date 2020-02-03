<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
Yii::$app->controller->renderPartial('schedule', ['model'=>$model]); ?><br/><br/>

<div class="pull-left">
    <?php
        echo Html::a("<i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak",
        ['mahasiswa/cetak-jadwal','jenis'=>$jn,'kr'=>$kr],
        ['class'=>' btn btn-info','target'=>'_blank',]);
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
        'heading'=>'<i class="fa fa-navicon"></i> Jadwal Kuliah',
    ],
    'columns' => [
         ['class' => 'kartik\grid\SerialColumn'],
         [
             'header'  => 'Matakuliah',
             'value' => function($data) {
		            return $data['mtk_nama'];
	            },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jam Masuk',
             'value' => function($data) {
                    return $data['jdwl_masuk'];
                },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Jam Keluar',
             'value' => function($data) {
                    return $data['jdwl_keluar'];
                },
             'format'  => 'raw',
         ],
         [
             'header'  => 'Ruangan',
             'value' => function($data) {
		            return $data['rg_kode'];
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