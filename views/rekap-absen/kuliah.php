<?php

use yii\helpers\Html;
use app\models\Funct;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Absensi Perkuliahan Hari Ini';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-absen-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute'=>'jdwl_hari',
				'value'=>function($model){
					return app\models\Funct::HARI()[$model->jdwl_hari];
				},
				'filter'=>app\models\Funct::HARI(),
				'width'=>'50px'
			],
			[
				'attribute'=>'jdwl_masuk',
				'width'=>'100px'
			],
            //'jdwl_keluar',
            //'mtk_kode',
			[
				'attribute'=>'mtk_nama',
				'format'=>'raw',
				'value'=>function($model){
					return "<b><u>".$model->dosen."</u><br />"
					.$model->mtk_nama."</b>";
					
				}
			],
			[
				'label'=>'Absen Dosen',
				'format'=>'raw',
				'value'=>function($model){
					$ket="";
					
					
					if($model->masuk){
						if(!$model->keluar){$ket = "Finger Keluar!";}
						$link=['/dirit/fix-absen','id'=>$model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2)];
						if($model->status==='2'){
							$ket =$model->keluar." < ".$model->jdwl_keluar;
							$ket = Html::a('<i class="fa fa-refresh"></i> '.$ket,$link,['style'=>'color:#000;','target'=>'_blank']);
						}else{
							if($model->hadir==0 and $model->maxAbsen>0){
								$link=['/dirit/acc-absen','id'=>$model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2)];
								$ket=' Terlambat '.$model->maxAbsen.' menit';
								if($model->status==='0'){
									$ket = Html::a('<i class="fa fa-refresh"></i> <span class="label label-danger"> '.$ket."</span>",$link,['style'=>'color:#000;','target'=>'_blank']);	
								}else{$ket='<span class="label label-info">'.$ket.'</span>';}
								
							}							
						}
					}
					
					return "<b>".$model->masuk. "</u> - "
					.$model->keluar."</b> "
					.($ket?'<br />'.$ket:"")
					;
				}
			],
            #'mtk_nama',
            #'dosen',
			[
				'header'=>'&sum;Mhs.',
				'width'=>'1%',
				'format'=>'raw',
				'value'=>function($model){
					return Html::a($model->mhs,['dirit/peserta-kuliah','id'=>$model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2)]);
				},
				
			],
			[
				'attribute'=>'absen',
				'header'=>'M|K|H',
				'value'=>function($model){
					return $model->M."|".$model->K."|".$model->absen;				
				},
				'width'=>'1%'
			],
			[
				'attribute'=>'hadir',
				'label'=>'Selesai',
				'value'=>function($model){
					$hadir=[0=>'Tidak','Ya'];
					return $hadir[$model->hadir];
					//return $model->status;
				},
				'width'=>'50px',
				'filter'=>['Tidak','Ya']
			],
			[
				'class'=>'kartik\grid\ActionColumn',
				'template'=>'
						<li>{fp_awal}</li>
						<li>{pAwal}</li>
						<li>{gDosen}</li>
						<li>{_m}</li>
					'
				,
				'buttons' => [
					'fp_awal'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-file"></i> Form Pulang Awal'
							,['trx-finger/form','id' => $model->jdwl_id,'view'=>'t','t'=>'1'],['target'=>'_blank']);
					},
					'pAwal'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Pulang Awal'
							,['rekap-absen/pulang-awal','id' => $model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2),'view'=>'t']);
					},
					'gDosen'=> function ($url, $model, $key) {
							return Html::a('<i class="glyphicon glyphicon-eye-open"></i> Ganti Pengajar'
							,['dosen-pengganti/create','id' => $model->jdwl_id,'view'=>'t']);
					},
					'_m'=> function ($url, $model, $key) {
							if($model->masuk){
								if(!$model->keluar){
									if(!Funct::acc('/dirit/i-keluar')){return false;}
									return Html::a('<i class="fa fa-upload"></i> Absen Keluar'
									,['dirit/i-keluar','id' => $model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2)],['target'=>'_blank']);									
    								}
								return false;
							}
							//if(!Funct::acc('/dirit/i-masuk')){return false;}
							return Html::a('<i class="fa fa-upload"></i> Masuk'
							,['dirit/i-masuk','id' => $model->jdwl_id,'m'=>substr($model->jdwl_masuk,0,2)],['target'=>'_blank']);
					},						
				],
				'dropdown'=>true,
				'dropdownOptions'=>['class'=>'pull-right'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
			],
        ],
    ]); 
	?>
</div>
