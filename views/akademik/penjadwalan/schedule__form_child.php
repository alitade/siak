<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Jadwal;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\time\TimePicker;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 * @var yii\widgets\ActiveForm $form
 */
$tmpt=Ruang::find()->all();
$jdwl=Jadwal::findOne($_GET['pid']);
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');
$sks =(int)$mtk->mtk_sks;
?>

<div class="schedule-form">
<div class="panel panel-primary">
    <div class="panel-heading">Input Subjadwal <?= $jdwl->GKode?></div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
		'type'=>ActiveForm::TYPE_HORIZONTAL,
        'id'    => 'form_jadwal',
		'fieldConfig'=>[
			'autoPlaceholder'=>true,
		]
	]); 

    $model->jdwl_masuk  =    "12:50";
    $model->jdwl_keluar =    "12:50";
	
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 5,
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
		'action'=>[
			'type'=> Form::INPUT_RAW, 
			'value'=>"<div>".
				Html::submitButton(
					$model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'),
					['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=>'subjdwl']
				)." ".Html::a('Batal',["ajr-view",'id'=>$model->bn->id],['class'=>'btn btn-success'])."</div>"
			,
		], 
        
    	
    ]


    ]);
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

 