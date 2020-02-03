<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DepDrop;
use kartik\widgets\Alert;


use app\models\Funct;

$this->title = 'Pergantian Jadwal Perkuliahan: '
.$model->jdwl->bn->ds->ds_nm.' | '
.Funct::HARI()[$model->jdwl->jdwl_hari].' '
.Funct::TANGGAL($model->tgl_perkuliahan).' '
.", ".$model->jdwl->jdwl_masuk." - ".$model->jdwl->jdwl_keluar." | Sesi $model->sesi"
;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Fingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transaksi-finger-create">
    <div class="panel panel-primary">
        <div class="panel-heading"><span class="panel-title"><h5><?= Html::encode($this->title) ?></h5></span></div>
        <div class="panel-body">
            <?php
            if($model->ds_stat==1){
                echo'<h4 style="font-weight:bold">Pergantian perkuliahan tidak bisa dilakukan dikarenakan status perkuliahan ini telah selesai</h4>';
            }else{
                if($msg!=''){
                    echo Alert::widget([
                        'type' => Alert::TYPE_DANGER,
                        'title' => 'Perhatian!!',
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'body' =>$msg,
                        'showSeparator' => true,
                        'delay' => false
                    ]);
                }
            ?>
                <div class="col-sm-12">
                    <?php if($Pergantian):?>
                        <table class="table">
                            <tr>
                                <th>Jadwal Pergantian</th>
                                <th>:</th>
                                <th><?=
                                    Funct::HARI()[$model->jdwl->jdwl_hari].' '
                                    .Funct::TANGGAL($Pergantian['tgl_ins']).' '
                                    .", $Pergantian[jdwl_masuk] - $Pergantian[jdwl_keluar]";

                                    ?></th>
                                <th><?= Html::a('Hapus',Url::to()."&k1=".$Pergantian['kode'],['class'=>'btn btn-success']) ?></th>
                            </tr>
                        </table>
                    <?php else:?>
                        <div class="transaksi-finger-form" style="clear:both">
                            <?php $form = ActiveForm::begin(); ?>
                            <?php
                            $dosen = Yii::$app->db->createCommand("exec dbo.dosenPengganti ".$id)->queryAll();
                            $dosen =ArrayHelper::map($dosen,'ds_id','ds_nm');

                            echo Form::widget([
                                'formName' =>'G',
                                'form' => $form,
                                'columns' => 3,
                                'attributes' => [
                                    /*
                                        'dsn'=>[
                                            'label'=>'Dosen',
                                            'options'=>['placeholder'=>'...'],
                                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                                            'options'=>[
                                                'data' => $dosen,
                                                'options' => [
                                                    'fullSpan'=>6,
                                                    'placeholder' => '... ',
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ],
                                        ],
                                    */
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
                    <?php endif;?>

                </div>
                <div class="col-sm-12">
                    <?php if($ModJdwl):?>
                        <hr />
                        <h4>INFO MATAKULIAH</h4>
                        <table class="table">
                            <thead>
                            <tr>
                                <th> Jurusan </th>
                                <th> Program </th>
                                <th> Kelas </th>
                                <th> Matakuliah </th>
                                <th> SKS </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($ModJdwl as $data):?>
                                <tr>
                                    <td><?=
                                        $data->bn->kln->jr->jr_jenjang." "
                                        .$data->bn->kln->jr->jr_nama
                                        ?></td>
                                    <td><?= $data->bn->kln->pr->pr_nama ?></td>
                                    <td><?= $data->jdwl_kls?></td>
                                    <td><?=
                                        $data->bn->mtk->mtk_kode.": "
                                        .$data->bn->mtk->mtk_nama
                                        ?></td>
                                    <td><?= $data->bn->mtk->mtk_sks ?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>


    </div>




</div>
