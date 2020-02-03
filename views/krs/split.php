<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Funct;
$this->title = 'Split Peserta Perwalian';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-title">
            <?= $model->bn->ds->ds_nm ?>
            <div class="pull-right">
            <?= $model->bn->kln->kr_kode.' / '. $model->bn->kln->jr->jr_jenjang.' '.$model->bn->kln->jr->jr_nama.' / '.$model->bn->kln->pr->pr_nama ?>
            </div>

        </span>

    </div>
    <div class="panel-body">
        Jadwal Utama
        <table class="table table-bordered">
            <tr>
                <th><?= $model->bn->mtk->mtk_kode.': '.$model->bn->mtk->mtk_nama." ($model->jdwl_kls) " ?></th>
                <th><?= Funct::getHari()[$model->jdwl_hari].', '.substr($model->jdwl_masuk,0,5).' - '.substr($model->jdwl_keluar,0,5) ?></th>
                <th><i class="fa fa-users"></i>: <?= $model->peserta ?></th>
            </tr>
        </table>
        <hr>
        <div class="box box-primary ">
            <div class="box-header"><span style="font-weight: bold;font-size: 14px;"> <u>Jadwal Pilihan Perpindahan Peserta Perwalian</u></span> </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <?php foreach($modelPilih as $d): ?>
                        <tr>
                            <th><?= $d->bn->kln->pr->pr_nama ?></th>
                            <th><?= $d->bn->ds->ds_nm ?></th>
                            <th><?= $d->bn->mtk->mtk_kode.' '.$d->bn->mtk->mtk_nama." ($d->jdwl_kls)" ?></th>
                            <th><?= Funct::getHari()[$d->jdwl_hari].', '.substr($d->jdwl_masuk,0,5).' - '.substr($d->jdwl_keluar,0,5) ?></th>
                            <th><i class="fa fa-users"></i> : <?= $d->peserta?:0 ?></th>
                            <th>

                                <?= Html::a('<i class="fa fa-arrow-left"></i> Pindahkan',['split-perkuliahan-manual','id'=>$model->jdwl_id,'split'=>$d->jdwl_id],['class'=>'btn btn-success btn-sm'])
                                ?>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

</div>
