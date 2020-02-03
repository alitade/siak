<?php

use yii\helpers\Html;

use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;


$tmpt=Ruang::find()->all();
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Pulang Awal: ';// . ' ' . $model->jdwl_id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwal Kuliah', 'url' => ['jdw']];
//$this->params['breadcrumbs'][] = ['label' => $model->jdwl_id, 'url' => ['view', 'id' => $model->jdwl_id]];
$this->params['breadcrumbs'][] = 'Ubah';

?>
<div class="jadwal-update">
	<div class="page-header">
        <h3><?= Funct::HARI()[date('w')].", ".date('Y-m-d H:i') ?></h3>
    </div>
    <div class="jadwal-form">
        <div class="panel panel-primary">
            <div class="panel-heading">Jadwal Kuliah</div>
            <div class="panel-body">
            <div class="col-sm-6">
            <fieldset><legend> Info Jadwal</legend>
            <table class="table" >
                <tr><th> Akademik </th><td> : </td><td><?= $model->bn->kln->kr_kode.' '.$model->bn->kln->kr->kr_nama ?></td></tr>
                <tr><th> Jurusan </th><td> : </td><td><?= $model->bn->kln->jr->jr_jenjang.": ".$model->bn->kln->jr->jr_nama?></td></tr>
                <tr><th> Dosen </th><td> : </td><td><?= $model->bn->ds->ds_nm ?></td></tr>
                <tr><th> Matakuliah </th><td> : </td><td><?= $model->bn->mtk->mtk_kode.": ".$model->bn->mtk->mtk_nama ?></td></tr>
                <!-- tr><th> Jadwal </th><td> : </td><td><?= Funct::HARI()[$model->jdwl_hari].', '.$model->jdwl_masuk."-".$model->jdwl_keluar ?></td></tr -->
                <tr><th> Absen Masuk </th><td> : </td><td><?= $viewAbsen['ds_masuk']?$viewAbsen['ds_masuk']:'-' ?></td></tr>
                <tr><th> Absen Keluar </th><td> : </td><td><?= $viewAbsen['ds_keluar']?$viewAbsen['ds_keluar']:'-' ?></td></tr>
            </table>
            </fieldset>
            </div>
        
            <div class="col-sm-6">
            <? if($vieJadwal):?>
            <fieldset><legend> Info Matakuliah</legend>
            <table class="table table-bordered">
            <thead>
            <tr><th>Kode</th><th>Matakuliah</th><th>Kelas</th><th>Program</th><th>Jurusan</th></tr>
            </thead>
            <tbody>
            <? foreach($vieJadwal as $d):?>
                <tr>
                    <td><?= $d['kode'] ?></td>
                    <td><?= $d['matakuliah'] ?></td>
                    <td><?= $d['kelas'] ?></td>
                    <td><?= $d['program'] ?></td>
                    <td><?= $d['jurusan'] ?></td>
                </tr>
            <? endforeach;?>
            </tbody>
            </table>
            </fieldset>
            <? endif; ?>
            
            </div>
            <? if(true
				/*
				date('w')==$model->jdwl_hari and 
				(!empty($viewAbsen['durasi']) and $viewAbsen['durasi']>0) and
				!empty($viewAbsen['ds_masuk'])
				*/
			):?>
            <div class="col-sm-12">
            <?php
            echo $modAwal->id;
		        $form = ActiveForm::begin([
					'type'=>ActiveForm::TYPE_HORIZONTAL,
					'options'=>[
						'Onsubmit'=>'return confirm("Ubah data ini?")'
					]
					
				]); 
    			echo Form::widget([
				'form' => $form,
				'formName' =>'awl',
				'columns' => 2,
				'attributes' => [
					//'bn_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'ID Bobot Nilai']], 
					//'semester'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Semester...']], 
					'keluar'=>[
						'label'=>'Jam Keluar',
						'type'=> Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\TimePicker', 
						'options'=>[
							'pluginOptions' => [
								'showSeconds' => false,
								'showMeridian' => false,
								'minuteStep' => 50,
							],
						]
					], 
					'ket'=>[
						'label'=>'*Alasan Keluar Awal',
						'type'=> Form::INPUT_TEXT,
						'options'=>['required'=>'required']						
					], 
					'ok'=>[
						'label'=>false,
						'type'=> Form::INPUT_RAW,
						'value'=>Html::submitButton('<i class="glyphicon glyphicon-save"></i> Simpan', ['class' =>'btn btn-success','style'=>'margin-top:4px'])
					], 
				]
			
			
				]);
				ActiveForm::end();
	    	?>
            </div>
            <? endif;?>
            </div>
        </div>
    </div>
</div>