<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Alert;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\BobotNilai $model
 * @var yii\widgets\ActiveForm $form
 */

$listSesi=[];
foreach($Detail as $d){
	$sesi=$d['sesi'];
	$listSesi[$d['sesi']]="Sesi $sesi, ".Funct::TANGGAL($d['tgl']);
	if(($d['ds_stat']==1) or $d['pergantian'] ){
		unset($listSesi[$d['sesi']]);
	}	
}


?>
<?php
if($Msg){
	echo Alert::widget([
		'type' => Alert::TYPE_DANGER,
		'title' => ':) !!',
		'icon' => 'glyphicon glyphicon-ok-sign',
		'body' => $Msg,
		'showSeparator' => true,
		'delay' => false
	]);
	
}
?>
<div class="pergantian-index">
	<table class="table">
    	<tr><th> <?= $model->bn->ds->ds_nm ?> </th></tr>
    	<tr><th> <?= \app\models\Funct::HARI()[$model->jdwl_hari].", $model->jdwl_masuk-$model->jdwl_keluar" ?> </th></tr>
    </table>
</div>


<div class="transaksi-finger-form" style="clear:both">
    <?php $form = ActiveForm::begin(); ?>
    <?php
    //$dosen = Yii::$app->db->createCommand("exec dbo.dosenPengganti ".$id)->queryAll();
    $dosen =ArrayHelper::map($dosen,'ds_id','ds_nm');
    
    echo Form::widget([
    'formName' =>'G',
    'form' => $form,
    'columns' =>4,
    'attributes' => [
        'sesi'=>[
            'label'=>'Sesi',
            'options'=>['placeholder'=>'...'],
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => $listSesi,
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => ' Sesi ',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ],
        ], 

        'tgl'=>[
            'label'=>'Tanggal',
            'type'=> Form::INPUT_WIDGET,
            'widgetClass'=>DateControl::classname(),
            'value'=>$_POST['G']['tgl'],
            'options'=>[
                'type'=>DateControl::FORMAT_DATE,'displayFormat'=>'php:Y-m-d',
                'options'=>[
                    'options'=>['required'=>'required']
                ]
            ]
        ], 
        'masuk'=>[
            'label'=>'Jam Masuk',
            'type'=> Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\TimePicker', 
            'value'=>$_POST['G']['masuk'],
            'options'=>[
                'pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 50,
                ],
                'options'=>['required'=>'required']
                
            ]
        ], 
        'keluar'=>[
            'label'=>'Jam Keluar','type'=> Form::INPUT_WIDGET, 
            'widgetClass'=>'\kartik\widgets\TimePicker', 
            'value'=>$_POST['G']['keluar'],
            'options'=>[
                'pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                    'minuteStep' => 50,
                ],
                'options'=>['required'=>'required']
            ]
        ], 
    ]


    ]);
    ?>
    <br />
    <div class="form-group">
        <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    </div>                    



<div class="col-sm-6">
<?php if($ModJdwl):?>
    <hr />
    <h4>INFO MATAKULIAH</h4>
    <table class="table">
        <thead>
        <tr><th> Matakuliah </th><th> SKS </th></tr>
        </thead>
        <tbody>
        <?php foreach($ModJdwl as $data):?>
        <tr>
            <td><?= "($data->jdwl_id)".$data->bn->ds_nidn." ".$data->bn->mtk->mtk_kode.": ".$data->bn->mtk->mtk_nama." ($data->jdwl_kls)"?></td>
            <td><?= $data->bn->mtk->mtk_sks ?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<div class="col-sm-6">
<?php if($Detail):?>
    <hr />
    <h4>INFO</h4>
    <table class="table">
        <thead>
        <tr>
            <th> Sesi</th>
            <th> Normal </th>
            <th> Pelaksanaan </th>
            <th> Pergantian </th>
            <th> Ket </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($Detail as $data):?>
        <tr>
            <td><?= "".$data['sesi']//$data->bn->mtk->mtk_sks ?></td>
            <td><?= Funct::TANGGAL($data[tgl]).'<br />'.$data['masuk'].'-'.$data['keluar'] ?></td>
            <td><?= Funct::TANGGAL($data[pelaksanaan]).'<br />'.$data['pelaksanaan_masuk'].'-'.$data['pelaksanaan_keluar'] ?></td>
            <td><?= Funct::TANGGAL($data[pergantian]).'<br />'.$data['pergantian_masuk'].'-'.$data['pergantian_keluar'] ?></td>
            <td><?php 
				$ket='<i class="glyphicon glyphicon-remove-circle" style="color:red;"></i>';	
				$btn='';
				if(Funct::StatAbsDsn($data['jdwl_id'],$data['sesi'])){$ket='<i class="glyphicon glyphicon-ok-circle" style="color:green;"></i>';}
				
				if($data['pergantian']){
					$btn=Html::a('Hapus',['dirit/pergantian-del',"id"=>$model->jdwl_id,'s'=>$data['sesi'],'d'=>1],['class'=>'btn btn-success']);
				}
				echo $ket;
			
			?></td>
            <td><?= $btn?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif; ?>
</div>

