<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;
use yii\helpers\Url;
	
$this->title = 'Dokumen Nilai';
$this->params['breadcrumbs'][] = ['label' => 'Dokumen Nilai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
    <?=
	'<table class="table">
		<tr>
			<td colspan="6">'
			.$ModJdwl->bn->kln->kr->kr_kode.', '.$ModJdwl->bn->kln->kr->kr_nama.
			'</td>
		</tr>
		<tr>
			<td> Matakuliah </td>
			<td> : ['.$ModJdwl->bn->mtk_kode.'] '.$ModJdwl->bn->mtk->mtk_nama.'</td>
			<td></td>
			<td>Jadwal Harian</td>
			<td> : '
			.\app\models\Funct::Hari()[$ModJdwl->jdwl_hari].", ".$ModJdwl->jdwl_masuk.'-'.$ModJdwl->jdwl_keluar 
			.' </td>
		<tr>
		<tr>
			<td> Dosen </td>
			<td> : '.$ModJdwl->bn->ds->ds_nm.'</td>
			<td></td>
			<td>Program</td>
			<td> : '.$ModJdwl->bn->kln->pr->pr_nama.' ('.$ModJdwl->jdwl_kls.')</td>
		<tr>
	</table>'
	?>		
    </div>
    <div class="panel-body">
		<?php
	$form = ActiveForm::begin([
    	'type'=>ActiveForm::TYPE_VERTICAL,
    	'id'=>'transkrip',
    	'action' => Url::to(['nilai/simpan-transkrip','id'=>$ModJdwl->jdwl_id]),
    	'method'=>'post'
    ]); 
	echo GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				['class' => 'kartik\grid\SerialColumn'],
				[
					'class'=>'kartik\grid\CheckboxColumn',
					'name'=>'chk[]',
					'checkboxOptions' => function($model, $key, $index, $column) {
						$nim	= $model->mhs_nim;
						$kode	= $model->jdwl->bn->mtk_kode;
						$tahun	= $model->jdwl->bn->kln->kr_kode;
						$cek=\app\models\Funct::CekKodeTranskrip("npm='$nim' and kode_mk='$kode' and (stat='0' or stat is null) and tahun='$tahun'" );
						if( $model->krs_grade=='-' || empty($model->krs_grade)|| $cek ){
							return ['hidden'=>true];	
						}
							return ['value'=>$model->krs_id];	
					    
					},
					'width'=>'36px',
					'headerOptions'=>['class'=>'kartik-sheet-style'],
				],
				'mhs_nim',
				[
					'header'=>'Nama',
					'format'=>'raw',
					'value'=>function($model){
						$nim	= $model->mhs_nim;
						$kode	= $model->jdwl->bn->mtk_kode;
						$att='';
						$cek=\app\models\Funct::CekKodeTranskrip("npm='$nim' and kode_mk='$kode' and (stat='0' or stat is null) 
						and tahun!='".$model->jdwl->bn->kln->kr_kode."'");
						if($cek){
							foreach($cek as $data){
								$att.=",($data->tahun=$data->huruf)";
							}
							$att = substr($att,1);
						}
						return $model->mhsNim->mhs->people->Nama."<br />$att";
					}
					
				],
				[
					'header'=>'Absensi',
					'format'=>'raw',
					'value'=>function($model){
                        $abs =Yii::$app->db->createCommand(" SELECT dbo.persensiMhs($model->krs_id) absen ")->queryOne();
                        if($abs){return	$abs['absen']."%";}
                        return "0%";
					}
				],
				'krs_tgs1',
	            'krs_tgs2', 
	            'krs_tgs3', 
	            'krs_tambahan', 
	            'krs_quis', 
	            'krs_uts', 
	            'krs_uas', 
	            'krs_grade', 
	            'krs_stat', 
			],
			'responsive'=>true,
			'hover'=>true,
			'condensed'=>true,
			'floatHeader'=>true,
			'panel' => [
				'heading'=>false,
				'after'=>
					Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Tambahkan Ke Transkrip',
						['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave']
					)
			],    
	        'toolbar'=>false
		]);
		
		ActiveForm::end();
		?>	    
    </div>
</div>