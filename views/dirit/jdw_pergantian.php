<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\select2\Select2;
use kartik\mpdf\Pdf;

$this->title = 'Jadwal Pergantian '.\app\models\Funct::TANGGAL(date('Y-m-d'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">
<div class="page-header"><h3><?= Html::encode($this->title) ?></h3></div>	
<br /><br />
    <?php 
	//Pjax::begin(); 
	echo $d= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar'=> [
            ['content'=>
                //Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah', ['dirit/jdw-create'],['class'=>'btn btn-success']).''.
				//Html::a('<i class="glyphicon glyphicon-download-alt"></i> Download PDF',Url::to().'&c=1',['class' => 'btn btn-info','target'=>'_blank'])
				Html::a('<i class="glyphicon glyphicon-repeat"></i> Kirim Ulang', ['resend'], ['class' => 'btn btn-info','onClick'=>"return confirm('Kirim Ulang Data?')"])
                //Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['dirit/report-jadwal-kuliah'],['class'=>'btn btn-info'])
            ],
            '{toggleData}',
			//'{export}',
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'	=> 'jr_id',
				'width'=>'20%',
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::JURUSAN(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Jurusan-'],
			],			
			[
				'attribute'	=> 'pr_kode',
				'width'=>'10%',
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>app\models\Funct::PROGRAM(), 
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder'=>'-Program-'],
			],			
			'sesi',
			[
				'label'=>'Awal',
				'format'=>'raw',
				'value'=>function($model){
					return \app\models\Funct::HARI()[$model->jdwl_hari]."<br />"
					.$model->jdwl_masuk.'-'.$model->jdwl_keluar;
					return $model->jAwal;
				},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'label'=>'Jam',
				'format'=>'raw',
				'value'=>function($model){
					return $model->pMasuk.'-'.$model->pKeluar;
				},
				'contentOptions'=>['class'=>'col-xs-1',],
			],
			[
				'attribute'=>'mtk_nama',
				'format'=>'raw',
				'value'=>function($model){
					return "<b>".$model->ds_nm."<br />".Html::decode($model->mtk_nama)."</b>";
				}
			],
			[
				'format'=>'raw',
				'header'=>'<i class="glyphicon glyphicon-user"></i>',
				'width'=>'5%',
				'value'=>function($model){
					return Html::a($model->peserta,
					Yii::$app->urlManager->createUrl(
						['dirit/jdw-detail','id' => $model->jdwl_id]),['title' => Yii::t('yii', 'Detail'),]
					);
				},
				'filter'=>false
			],
			[
				'header'=>' ',
				'format'=>'raw',
				'value'=>function($model){
						$btn =Html::a('<i class="glyphicon glyphicon-refresh" id="_'.$model_id.'"></i>',['resend','id'=>$model->jdwl_id,'s'=>$model->sesi],['title'=>'Resend']);
					if($model->xx){
						$btn ='<i class="glyphicon glyphicon-ok-circle" style="color:green"></i>';
					}
					return $btn;
				}
			],
        ],
        'responsive'=>false,
        'hover'=>true,
        'condensed'=>true,
		'showPageSummary'=>true, 
        'panel'=>[
        	'type'=>GridView::TYPE_PRIMARY,
        	'heading'=>'<i class="fa fa-navicon"></i> Daftar Pergantian Perkuliahan',
			'after'=>false,
    	]
    ]); //Pjax::end(); 

	?>

</div>
