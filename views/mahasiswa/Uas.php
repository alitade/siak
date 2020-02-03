<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
Yii::$app->controller->renderPartial('schedule', ['model'=>$model]); 

if($lunas){
?><br/><br/>

<div class="pull-left">
    <?php
        echo Html::a(" <i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak KPU A4",
        ['mahasiswa/cetak-kpu','kr'=>$kr,'t'=>'uas'],
        ['class'=>' btn btn-success','target'=>'_blank',])
		.' '.Html::a(" <i class='glyphicon glyphicon-print glyphicon-white'></i> Cetak KPU A5",
        ['mahasiswa/cetak-kpu','kr'=>$kr,'t'=>'uas','s'=>5],
        ['class'=>' btn btn-success','target'=>'_blank',])
		;
    ?>
</div>
<br />
<br />
<br />
<?php
#    \app\models\Funct::v($dataProvider);
// var_dump($dataProvider);
// die();
	echo 
	GridView::widget([
		'dataProvider' => @$dataProvider,
		'filterModel' => false,
		'panel'=>[
			'type'=>GridView::TYPE_PRIMARY,
			'heading'=>'<i class="fa fa-navicon"></i> Jadwal UAS',
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
				 'header'  => 'Jadwal UAS',
				 'value' => function($data) {
                     $tgl = explode(" ",$data['jdwl_uas']);
                     if($data['jdwl_uas']){
                         $h = date('N',strtotime($tgl[0]));
                         return '<span class="text-nowrap">'.\app\models\Funct::getHari()[$h].', '.\app\models\Funct::TANGGAL($tgl[0]).'</span>';
                     }
                     return '-';

                 },
				 'format'  => 'raw',
			 ],
			 [
				 'header'  => 'Jam',
				 'value' => function($data) {
                     $tgl = explode(" ",$data['jdwl_uas']);
                     return substr($tgl[1],0,5);
					},
				 'format'  => 'raw',
			 ],
			 [
				 'header'  => 'Ruangan',
				 'value' => function($data) {
						return $data['rg_uas'];
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

}else{
	echo "<center>
			<div style='color:red;font-weight:bold;font-size:16pt'> Silahkan Melunasi Tunggakan Kuliah Untuk Mengkases Fasilitas Cetak KPU  </div>
		</center>";	
}

?>