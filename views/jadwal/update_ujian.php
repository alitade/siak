<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\builder\Form;
use app\models\Ruang;
use app\models\Funct;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\Jadwal $model
 */

$this->title = 'Ubah Jadwal: ' . ' ' . $model->jdwl_id;
$this->params['breadcrumbs'][] = ['label' => 'Jadwals', 'url' => ['jdw']];
$this->params['breadcrumbs'][] = ['label' => $model->jdwl_id, 'url' => ['view', 'id' => $model->jdwl_id]];
$this->params['breadcrumbs'][] = 'Ubah';

$tmpt=Ruang::find()->all();
$ruang=ArrayHelper::map($tmpt, 'rg_kode', 'rg_nama');
$har=Funct::getHari1();
$hari=ArrayHelper::map($har, 'id', 'nama');

$q="select dbo.validasiJadwal(jdwl_id) t from tbl_jadwal where jdwl_id='$model->jdwl_id'";
$q=Yii::$app->db->createCommand($q)->queryOne();
if(
//$q['t']>0
false
){
	echo "<div class='alert alert-success'>Jadwal sudah memiliki Peserta</div>";
}else{
?>
<div class="box box-primary">
    <div class="box-header" style="font-weight: bold;font-size: 14px;">
        Perubahan Jadwal Ujian
        <div class="pull-right">
            <?= $model->bn->kln->kr_kode ?> / <?= $model->bn->kln->jr->jr_jenjang.' '.$model->bn->kln->jr->jr_nama ?> (<?= $model->bn->kln->pr->pr_nama ?>)
        </div>
        <div class="clearfix"></div>
        <?= $model->bn->ds->ds_nm ?>
        <div class="pull-right">
            <?= $model->bn->mtk->mtk_kode.': '.$model->bn->mtk->mtk_nama?> (<?= $model->jdwl_kls?>) | <?= \app\models\Funct::getHari()[$model->jdwl_hari].' ,'.$model->jdwl_masuk.'-'.$model->jdwl_keluar?>
        </div>
    </div>
</div>

    <div class="panel">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]); ?>
            <div class="col-sm-6">
                <div class="row">
                    <div class="box box-primary">
                        <div class="box-header" style="font-weight: bold;font-size: 14px;">Jadwal UTS</div>
                        <div class="box-body">
                            <?php
                            echo Form::widget([
                                'model' => $model,
                                'form' => $form,
                                'columns' => 1,
                                'attributes' => [
                                    'rg_uts'=>[
                                        'label'=>'Ruangan',
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\widgets\Select2',
                                        'options'=>['data'=>$ruang],
                                    ],
                                    'jdwl_uts'=>[
                                        'label'=>'Tanggal',
                                        'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
                                        'options'=>[
                                            'attribute' => 'jdwl_uts',
                                            'pluginOptions' => ['format' => 'yyyy-mm-dd',]
                                        ]
                                    ],
                                    'uts'=>[
                                        'label'=>'Jam',
                                        'columns'=>2,
                                        'labeSpan'=>2,
                                        'attributes'=>[
                                            'uts_masuk'=>[
                                                'type'=> Form::INPUT_WIDGET,
                                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                                'options'=>[
                                                    'pluginOptions' => [
                                                        'showSeconds' => false,
                                                        'showMeridian' => false,
                                                        'minuteStep' => 50,
                                                    ],
                                                ]
                                            ],
                                            'uts_keluar'=>[
                                                'type'=> Form::INPUT_WIDGET,
                                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                                'options'=>[
                                                    'pluginOptions' => [
                                                        'showSeconds' => false,
                                                        'showMeridian' => false,
                                                        'minuteStep' => 50,
                                                    ],
                                                ]
                                            ],


                                        ],
                                    ]
                                ]
                            ]);
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="box box-primary">
                        <div class="box-header" style="font-weight: bold;font-size: 14px;">Jadwal UAS</div>
                        <div class="box-body">
                            <?php
                            echo Form::widget([
                                'model' => $model,
                                'form' => $form,
                                'columns' => 1,
                                'attributes' => [
                                    'rg_uas'=>[
                                        'label'=>'Ruangan',
                                        'type'=>Form::INPUT_WIDGET,
                                        'widgetClass'=>'\kartik\widgets\Select2',
                                        'options'=>['data'=>$ruang],
                                    ],
                                    'jdwl_uas'=>[
                                        'label'=>'Tanggal',
                                        'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),
                                        'options'=>[
                                            'attribute' => 'jdwl_uas',
                                            'pluginOptions' => ['format' => 'yyyy-mm-dd',]
                                        ]
                                    ],
                                    'uas'=>[
                                        'label'=>'Jam',
                                        'columns'=>2,
                                        'labeSpan'=>2,
                                        'attributes'=>[
                                            'uas_masuk'=>[
                                                'type'=> Form::INPUT_WIDGET,
                                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                                'options'=>[
                                                    'pluginOptions' => [
                                                        'showSeconds' => false,
                                                        'showMeridian' => false,
                                                        'minuteStep' => 50,
                                                    ],
                                                ]

                                            ],
                                            'uas_keluar'=>[
                                                'type'=> Form::INPUT_WIDGET,
                                                'widgetClass'=>'\kartik\widgets\TimePicker',
                                                'options'=>[
                                                    'pluginOptions' => [
                                                        'showSeconds' => false,
                                                        'showMeridian' => false,
                                                        'minuteStep' => 50,
                                                    ],
                                                ]
                                            ],


                                        ],
                                    ]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= Html::submitButton('<i class="fa fa-save"></i> Simpan Perubahan',['class'=>'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
}
?>
