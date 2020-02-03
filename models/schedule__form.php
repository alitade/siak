<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\time\TimePicker;
use kartik\grid\GridView;

use app\models\Ruang;
use app\models\Funct;


/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$tmpt=Ruang::find()->all();
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');
$sks =(int)$mtk->mtk_sks;
?>

<div class="jadwal-form">
<div class="panel panel-primary">
    <div class="panel-heading">Input Jadwal</div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
		'type'=>ActiveForm::TYPE_HORIZONTAL,
        'id'    => 'form_jadwal',
		'fieldConfig'=>[
			'autoPlaceholder'=>true,
		]
	]); 

    $model->jdwl_masuk  =($model->jdwl_masuk?$model->jdwl_masuk:"12:50");
    $model->jdwl_keluar =($model->jdwl_keluar?$model->jdwl_keluar:"12:50");
	
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 6,
    'attributes' => [
		    'jdwl_hari'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>$hari,
				'options'=>['placeholder'=>'Hari',]
			],
        ],  
    	'jdwl_masuk'=>['type'=> Form::INPUT_WIDGET, 
        'widgetClass'=>'\kartik\widgets\TimePicker', 
                'options'=>[
                   'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 50,
                        ],
                    ]
        ], 

    	'jdwl_keluar'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\TimePicker', 
                'options'=>[
                   'pluginOptions' => [
                            'template' =>false,
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 50,
                            'showInputs' => false,
                            'disableFocus' =>true,
                            'disableMousewheel' =>true,
                        ],
                    //'readonly' => 'readonly'
                    ]
        ], 
        'rg_kode'=>[
            'type'=>Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\Select2', 
            'options'=>[
				'data'=>$ruang,
				'options'=>['placeholder'=>'Ruangan',]
			], 
        ],	
    	'jdwl_kls'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Kelas']], 
		'action'=>[
			'type'=> Form::INPUT_RAW, 
			'value'=>
				($dataProvider3?
					Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Perbaiki Jadwal') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']):
					Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
				)
			
				
			,
			'options'=>[
				'placeholder'=>'Kelas'
			]
		], 
        
    	
    ]


    ]);

	if($dataProvider3){
	echo'
		<div style="clear:both"></div>
     	<div class="col-sm-12" style="border:solid #000 1px;padding:2px;font-weight:bold">
        	Penggabungan jadwal hanya berlaku untuk jurusan dan program perkuliahan yang berbeda.<br />
            Fasilitas ini berfungsi untuk menyamakan jadwal perkuliahan jadwal yang di pilih ke jadwal tujuan.
     	</div>
		<div style="clear:both"></div>
	';
		
	echo "<br />".GridView::widget([
        'dataProvider' => $dataProvider3,
        'toolbar'=>false,
		'layout'=>'{items}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label'=>' ',
				'format'=>'raw',
				'value'=>function($model){
					return Html::radio('GKode',false, ['value' => $model->GKode,'id'=>'get']);
				},
			],
			[
				'class'=>'kartik\grid\ExpandRowColumn',
				'width'=>'50px',
				'value'=>function ($model, $key, $index, $column) {
					return GridView::ROW_COLLAPSED;
				},
				'detail'=>function ($model, $key, $index, $column)use($ModBn) {
					//return $ModBn->kln->kr_kode." ".$ModBn->ds_nidn;
					$searchModel = new \app\models\JadwalSearch;
					$dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),
					" ds.ds_id='".$ModBn->ds_nidn."'  and kr_kode='".$ModBn->kln->kr_kode."'
					and(
						(ISNUMERIC('".$model->GKode."')=1 and '".$model->GKode."'=jdwl_id)
						or 
						(ISNUMERIC('".$model->GKode."')=0 and '".$model->GKode."'=GKode)
					)
					and(kl.jr_id!='".$model->bn->kln->jr_id."' and kl.pr_kode !='".$model->bn->kln->pr_kode."')
					");
					return Yii::$app->controller->renderPartial('jdw_dosen', [
						'dataProvider' => $dataProvider,
						'searchModel' => $searchModel,
					]);
				},
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'expandOneOnly'=>true
			],
			[
				'attribute'=>'jdwl_hari',
				'label'=>'Jadwal Perkuliahan',
				'value'=>function($model){
					return @app\models\Funct::HARI()[@$model->jdwl_hari].', '.$model->jadwal;
				},
			],
			'jum',
			[
				'attribute'=>'jumjdw',
				'label'=>'Jumlah Jadwal',
			],
			[
				'attribute'=>'GKode',
				'label'=>'Kode Group',
			]
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel'=>[
			'heading'=>'Jadwal Bentork',
			'after'=>Html::submitButton(Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Gabungkan'),['class' =>'btn btn-success','name'=>'GB']),
			'footer'=>false,
		]
    ]);
	}


?>
    </div>
    </div>    
</div>

<?php ActiveForm::end(); ?>

<?php

$this->registerJs("
    
    $(document).ready(function(){
 
 

        $('#jadwal-jdwl_masuk').on('changeTime.timepicker', function(e) {
            var curtime = e.timeStamp;
            curtime += ($sks * 50) * 60;
            var hours = Math.floor(curtime/60/60),
            mins = Math.floor((curtime - hours * 60 * 60) / 60),
            output = hours%24+':'+mins;

            console.log(e.timeStamp.toHHMMSS());

        });


    });

    Number.prototype.toHHMMSS = function() {
      var hours = Math.floor(this / 3600) < 10 ? ('00' + Math.floor(this / 3600)).slice(-2) : Math.floor(this / 3600);
      var minutes = ('00' + Math.floor((this % 3600) / 60)).slice(-2);
      var seconds = ('00' + (this % 3600) % 60).slice(-2);
      return hours + ':' + minutes + ':' + seconds;
    }

    function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes * 60000);
}

", yii\web\View::POS_END, 'my-options');

?>

 