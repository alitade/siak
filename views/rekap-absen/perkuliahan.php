<?php
use yii\helpers\Html;
use kartik\grid\GridView;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransaksiFingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Absensi Perkuliahan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-absen-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="angge-search">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
            <?= 
                $form->field($searchModel, 'krkd')->widget(Select2::classname(), [
                    'data' =>app\models\Funct::AKADEMIK(),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Tahun Akademik','required'=>'required'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
             ?>
             
            <?php // echo $form->field($model, 'Tipe') ?>
        
            <div class="form-group" style="text-align: right;">
                <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['dirit/jdw'],['class' => 'btn btn-danger']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php	
	if($_GET['RekapSearch']){
		echo GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				[
					'attribute'=>'tgl_ins',
					'width'=>'100px'
				],
				[
					'attribute'=>'sesi',
					'width'=>'30px'
				],
				[
					'attribute'=>'jdwl_hari',
					'value'=>function($model){
						//return $model->jdwl_hari;
						return app\models\Funct::HARI()[$model->jdwl_hari];
					},
					'filter'=>app\models\Funct::HARI(),
					'width'=>'50px'
				],
				[
					'attribute'=>'jdwl_masuk',
					'width'=>'100px'
				],
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
							if($model->status==='2'){$ket = $model->keluar." < ".$model->jdwl_keluar;}
						}
						
						return "<b>".$model->masuk."</u> - "
						.$model->keluar."</b>"
						.($ket?'<br /><span class="label label-danger"> '.$ket.'</span>':"")
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
						return Html::a($model->mhs,['dirit/peserta-kuliah','id'=>$model->jdwl_id]);
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
					'template'=>'<li>{edit}</li><li>{Fganti}</li><li>{ganti}</li>',
					'buttons' => [
						'edit'=> function ($url, $model, $key) {
							$kode= str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id;
							return Html::a('<i class="glyphicon glyphicon-pencil"></i> Update Kehadiran'
							,['rekap-absen/view','id' => $kode,'view'=>'t']);
						},						
						'Fganti'=> function ($url, $model, $key) {
							//if($model->status==1){return false;}
							$kode= str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
							return Html::a('<i class="glyphicon glyphicon-file"></i>Form Pergantian Jadwal'
							,['trx-finger/form-pergantian','id' =>$model->jdwl_id,'k'=>$kode,'view'=>'t']);
						},						
						'ganti'=> function ($url, $model, $key){
							//if($model->status==1){return false;}
							
							$kode= str_replace('-','',$model->tgl_ins).$model->jdwl_hari.$model->jdwl_id.substr($model->jdwl_masuk,0,2);
							
							return Html::a('<i class="glyphicon glyphicon-file"></i>Input Pergantian Jadwal'
							,['rekap-absen/create-pergantian','id' =>$model->jdwl_id,'k'=>$kode,'view'=>'t']);
						},						
					],
					'dropdown'=>true,
					'dropdownOptions'=>['class'=>'pull-right'],
					'headerOptions'=>['class'=>'kartik-sheet-style'],
				],
			],
		]); 
	}
	?>
</div>
