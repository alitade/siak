<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Funct;


$this->title = 'Split Peserta Perkuliahan';
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
        <div class="text-center"> <h3>Perpindahan Peserta Perkuliahan</h3> </div>

        <table class="table table-bordered table-condenced">
            <tr>
                <th><?= $model->bn->ds->ds_nm ?></th>
                <th><?= $model->bn->mtk->mtk_kode.': '.$model->bn->mtk->mtk_nama." ($model->jdwl_kls) " ?></th>
                <th><?= Funct::getHari()[$model->jdwl_hari].', '.substr($model->jdwl_masuk,0,5).' - '.substr($model->jdwl_keluar,0,5) ?></th>
                <th>
                    <i class="fa fa-user"></i> : <?= $model->peserta ?> /
                    <?= Html::a('<i class="fa fa-users"></i> '. $gab['master']['tot'],['#'],['class'=>'']); ?>

                </th>
            </tr>
            <tr><th colspan="3" class="text-center"><i class="fa fa-arrow-down"></i></th></tr>
            <tr>
                <th><?= $modelPilih->bn->ds->ds_nm ?></th>
                <th><?= $modelPilih->bn->mtk->mtk_kode.': '.$modelPilih->bn->mtk->mtk_nama." ($modelPilih->jdwl_kls) " ?></th>
                <th><?= Funct::getHari()[$modelPilih->jdwl_hari].', '.substr($modelPilih->jdwl_masuk,0,5).' - '.substr($modelPilih->jdwl_keluar,0,5) ?></th>
                <th>
                    <i class="fa fa-user"></i> : <?= $modelPilih->peserta?:0 ?> /
                    <?= Html::a('<i class="fa fa-users"></i> '. $gab['sub']['tot'],['#'],['class'=>'']); ?>
                </th>
            </tr>

        </table>
        <hr>
        <?php

        $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_VERTICAL,
        'id'=>'create-krs',
        'action' => Url::to(['krs/splitm-perkuliahan-proc','id'=>$model->jdwl_id,'split'=>$modelPilih->jdwl_id]),
        'method'=>'post'
        ]);
        ?>
        <?=
        GridView::widget([
            'dataProvider' => $data,
            'id'=>'krs-grid',
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'checkboxOptions' => function ($data, $key, $index, $column)use($bentrok) {
                        if(isset($bentrok[$data['mhs_nim']])){return ['value' =>false,'hidden'=>true];}
                        return ['value' => $data['id']];
                    }
                ],
                [
                    'header'  => 'NPM',
                    'value' => function($data) {
                        return $data[mhs_nim];

                    },
                    #'visible'=>$kuota==0?false:true,
                    'format'  => 'raw',
                ],
                [
                    'header'  => 'NPM',
                    'value' => function($data) {
                        return $data[Nama];

                    },
                    #'visible'=>$kuota==0?false:true,
                    'format'  => 'raw',
                ],
                [
                    'label'=>'Ket.Bentrok',
                    'format'=>'raw',
                    'value'=>function($data)use($bentrok){return count($bentrok[$data['mhs_nim']])>0 ?implode("<br>",$bentrok[$data['mhs_nim']]):"-";}
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'layout' =>false,
            'panel'=>[
                'type'=>GridView::TYPE_DEFAULT,
                'heading'=>'Peserta',
                'before'=>' ' ,
                'footer'=>false,
                'after'=>Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Simpan', ['class' => 'btn btn-danger','id' => 'button', 'name' => 'btnsave']),
            ],
            'toolbar'=>false
        ]);
        ?>
        <?php ActiveForm::end();?>

    </div>

</div>
