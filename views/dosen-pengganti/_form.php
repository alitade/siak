<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Funct;

/**
 * @var yii\web\View $this
 * @var app\models\DosenPengganti $model
 * @var yii\widgets\ActiveForm $form
 */
$dosen = Yii::$app->db->createCommand("exec dbo.dosenPengganti ".$jadwal->jdwl_id)->queryAll();
$dosen =ArrayHelper::map($dosen,'ds_id','ds_nm');
?>
<div class="page-header">
    <h3><?= Html::encode($this->title) ?></h3>
</div>
 
 
<table class="table">
  <tr>
    <th>Tahun Akademik</th>
    <td><?=$jadwal->bn->kln->kr->kr_nama?></td>
    <th>Jurusan</th>
    <td><?=Funct::JURUSAN()[$jadwal->bn->kln->jr_id]." (".Funct::PROGRAM()[$jadwal->bn->kln->pr_kode].")"?></td>
  </tr>
  <tr>
    <th>Matakuliah</th>
    <td><?=Funct::Mtk()[$jadwal->bn->mtk_kode]?></td>
    <th>Dosen</th>
    <td><?=$jadwal->bn->ds->ds_nm?></td>
  </tr>
  <tr>
    <th>Ruang</th>
    <td><?=$jadwal->rg->rg_nama?></td>
    <th>Kelas</th>
    <td><?=$jadwal->jdwl_kls?></td>
  </tr>
  <tr>
    <th>Hari</th>
    <td><?=Funct::HARI()[$jadwal->jdwl_hari]?></td>
    <th>Jam</th>
    <td><?=$jadwal->jdwl_masuk.' - '.$jadwal->jdwl_keluar?></td>
  </tr>
</table>
<div class="col-sm-12">
<div class="dosen-pengganti-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'Id'=>['label' => false,'type'=> Form::INPUT_HIDDEN, 'options'=>['placeholder'=>'Enter ID...']],
            'Tgl' =>['label'=>'Tanggal','type' => Form::INPUT_WIDGET, 'widgetClass'=>'kartik\select2\Select2',  
                    'options' => [
                            'data' => $tanggals,
                            'options' => ['placeholder' => 'Pilih Tanggal'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],


            ],

            'ds_id' =>['label'=>'Dosen Pengganti',
                        'type'=>Form::INPUT_WIDGET, 
                                'widgetClass'=>'kartik\select2\Select2',
                                'options' => [
                                    'data' => $dosen,
                                    'options' => ['placeholder' => 'Pilih Dosen'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ],
            ],

            //'ds_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Ds ID...']],

        ]

    ]);
    echo "<hr>";
    echo Html::submitButton($model->isNewRecord ?  'Simpan' : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>


<? if($pengganti):?> 
<table class="table	">
	<thead>
	<th>Tanggal</th>
	<th>Dosen</th>
	<th> </th>
    </thead>
    <tbody>
<? foreach($pengganti as $data):?>
	<tr>
        <td><?= $data['tgl']." (Pertemuan Ke-$data[sesi]) "?></td>
        <td><?= $data['ds_nm']?></td>
        <td><?= $data['hadir']=='1'?'Hadir':'-'?></td>
    </tr>
<? endforeach; ?>
	</tbody>
</table>
<? endif;?>
</div>
