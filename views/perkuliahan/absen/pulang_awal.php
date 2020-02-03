<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\TransaksiFinger */



$this->title = "Pulang Awal";
$this->params['breadcrumbs'][] = ['label' => 'Perkuliahan Hari Ini', 'url' => ['berjalan']];
$this->params['breadcrumbs'][] = $this->title;

if(model){
    $del=Html::a('<i class="fa fa-times-circle"></i>',['id'=>$model->id,'d'=>1],['style'=>'color:red']);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><h4 class="panel-title"><b><?= "[".\app\models\Funct::HARI()[$model->jdwl_hari].", ".substr($model->jdwl_masuk,0,5)." ".substr($model->jdwl_keluar,0,5)."] ".$model->dosen->ds_nm ?></b></h4></div>
        <div class="panel-body">

            <div class="col-sm-12">
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
                    'model' => $keluar,
                    'form' => $form,
                    'columns' =>3,
                    'attributes' => [
                        'jdwl_keluar' =>['label'=>'Jam','type'=>Form::INPUT_TEXT,],
                        'ket' =>['label'=>'Alasan','type'=>Form::INPUT_TEXT,],
                        [
                            'type'=>Form::INPUT_RAW,'value'=>Html::submitButton('Save',['class' =>'btn btn-primary'])
                        ]
                    ]

                ]);
                ActiveForm::end(); ?>
                <hr>
            </div>

            <div class="col-sm-12">
                <p></p>
                <?php if($jadwal):?>
                    <table class="table table-bordered">
                        <thead>
                        <tr><th colspan="3">Dosen Pengganti: <b><?= ($model->pengganti->ds_nm?$model->pengganti->ds_nm." ".$del :"Tidak Ada") ?></b></th></tr>
                        <tr>
                            <th>No</th>
                            <th>Matakuliah</th>
                            <th><i class="fa fa-users"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no=0;$totMhs=0; foreach($jadwal as $d):$no++;$totMhs+=$d->totMhs; ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= "[$d->mtk_kode] $d->mtk_nama ($d->jdwl_kls)" ?></td>
                                <td><?= $d->totMhs ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tr>
                            <th colspan="2">TOTAL <i class="fa fa-users"></i></th>
                            <th><?= $totMhs ?></th>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>

        </div>

    </div>
<?php }else{ ?><h4>DATA TIDAK DI TEMUKAN</h4> <?php }?>