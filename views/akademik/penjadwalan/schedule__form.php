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
    <div class="panel-body">
    <?php 
	if(empty($model->jdwl_masuk)){$model->jdwl_masuk  =    "12:50";}
	if(empty($model->jdwl_keluar)){$model->jdwl_keluar=    "12:50";}
	
	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' =>1,
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
		'jam'=>[
			'label'=>'Jam',
			'columns'=>2,
			'labeSpan'=>2,
			'attributes'=>[
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
				'jdwl_keluar'=>['type'=> Form::INPUT_WIDGET,
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

			],
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


 