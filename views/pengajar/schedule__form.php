<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\time\TimePicker;

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
    <div class="panel-heading">
    	<div class="col-sm-6">
    	Input Jadwal 
		<?= $mod->kln->jr->jr_jenjang." ".$mod->kln->jr->jr_nama." (".$mod->kln->pr->pr_nama.")" ?><br />
        <?= $mod->mtk->mtk_kode.": ".$mod->mtk->mtk_nama." (".$mod->mtk->mtk_sks." Sks)" ?>
        </div>
    	<div class="col-sm-6">
    	Tahun Akademik : <?= $mod->kln->kr_kode ?><br /><?= $mod->ds->ds_nm ?>
        </div>
        <div style="clear:both"></div>
    </div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
		'type'=>ActiveForm::TYPE_HORIZONTAL,
        'id'    => 'form_jadwal',
		'fieldConfig'=>[
			'autoPlaceholder'=>true,
		]
	]); 

	if(empty($model->jdwl_masuk)){$model->jdwl_masuk  =    "12:50";}
	if(empty($model->jdwl_keluar)){$model->jdwl_keluar=    "12:50";}
	
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
				'options'=>[
					'placeholder'=>'Hari',
					'readonly'=>($B?true:false)
				]
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
				'readonly'=>($B?true:false)
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
				Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="glyphicon glyphicon-save"></i> Simpan') : Yii::t('app', '<i class="glyphicon glyphicon-edit"></i> Ubah'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
			,
			'options'=>[
				'placeholder'=>'Kelas'
			]
		], 
        
    	
    ]


    ]);
?>
<?php if($qBentrok):?>
	<div class="col-sm-12">
    <h4>Daftar Pilihan Penggabungan Jadwal</h4>

	<table class="table">
    	<thead>
        <tr>
        	<th>No</th>
        	<th>Jurusan</th>
        	<th>Program</th>
        	<th>(SKS) Matakuliah</th>
        	<th>Jadwal</th>
        	<th>Kelas</th>
        </tr>
        </thead>
         <tbody>
    <?php 
		$n=0; foreach($qBentrok as $d): $n++;
		$jdwl=explode("|",$d['jadwal']);
		$jd = "";
		foreach($jdwl as $k=>$v){
			$Info=explode('#',$v);
			$ket=app\models\Funct::HARI()[$Info[1]].", $Info[2]-$Info[3] | $Info[4]";
			$jd .=$ket."<br />";
		}
		$jdwl=$jd;
		#$jdwl=implode("<br />",$jdwl);
		
		
	?>
		<tr>
        	<td><?= Html::radio('gab',false,['value'=>$d['id'],'required'=>'required'])?></td>
        	<td><?= $d['jenjang'].' '.$d['jurusan']?></td>
        	<td><?= $d['program']?></td>
        	<td><?= "($d[sks]) ".$d['mtk'].': '.$d['matkul']?></td>
        	<td><?= $jdwl?></td>
        	<td><?= $d['kls']?></td>
        </tr>	
	<?php endforeach;?>
    </tbody>
    </table>	
	<?php 
	echo Html::submitButton('<i class="glyphicon glyphicon-save"></i> Gabungkan', ['class' =>'btn btn-success','name'=>'gabung']);
	?>
    </div>
<?php endif; ?>
		
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

 