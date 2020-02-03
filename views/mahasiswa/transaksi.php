<?php

use kartik\helpers\Html;


use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

use yii\web\View;
use yii\web\JsExpression;



#Funct::v($listTarif);
$this->title = 'Transaksi Pembayaran';

?>
<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"><?= $this->title ?></span>
        </div>
    </div>

    <div class="panel-body">
        <?php
        $form= ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,
            'action'=>\yii\helpers\Url::to(['transaksi']),
            'method'=>'get',
            'formConfig'=>['labelSpan'=>1],
        ]);
        echo Form::widget([
            'formName' =>'id',
            'form' => $form,
            'columns'=>2,
            'attributes' => [
                [
                    'label'=>false,#'[No. Ktp] Nama',
                    'type'=>Form::INPUT_RAW,
                    'value'=>
                        \kartik\select2\Select2::widget([
                            'name' => 'id',
                            'hideSearch' => true,
                            'options' => ['placeholder' => 'NPM / Nama',],
                            'pluginOptions' => [
                                #'width' => '100%',
                                'allowClear' => true,
                                'minimumInputLength' =>5,
                                'language' => ['errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),],
                                'ajax' => [
                                    'url' => \yii\helpers\Url::to(['json/cari-npm']),
                                    'dataType' =>'json',
                                    'data' => new JsExpression('function(params){return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function(markup){ return markup; }'),
                                'templateResult' => new JsExpression('function(bio){ return bio.text; }'),
                                'templateSelection' => new JsExpression('function(bio){ return bio.text; }'),
                            ],
                        ]),

                ],
                [
                    'type'=>Form::INPUT_RAW,
                    'value'=>Html::submitButton('<i class="fa fa-search"></i> Cari Mahasiswa',['class'=>'btn btn-success'])
                ]
            ]
        ]);
        ActiveForm::end();
        ?>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);"><span style="font-size:14px;font-weight:bold">
                Fakultas : <?= $model->jr->fk->fk_nama ?> /
                Program Studi: <?= $model->jr->jr_jenjang.' '.$model->jr->jr_nama ?>

            </span></div>
        <div style="clear: both"></div>
    </div>

    <div class="raw">
        <div class="col-md-2">
            <?php
            $Status = [1 => 'Baru', 'Linier', 'Non Linier'];
            $img="no_foto.jpg";
            if($model->bio->photo!=''){$img=$model->bio->photo;}
            echo Html::img("@web/pt/$img",['class'=>'img-thumbnail'])?>
            <p></p>
        </div>
        <div class="col-md-10">
            <table class="table table-bordered table-condensed">
                <tr><th> Program Perkuliahan / Kategori </th><td><?= $model->pr->pr_nama ?> /  <?= $Status[$model->status_pendaftaran]?></td></tr>
                <tr><th> NPM / Nama </th><td><?= $model->npm ?> / <?= $model->bio->nama ?> </td></tr>
                <tr><th> Angkatan / Kurikulum Masuk </th><td>  <?= $model->angkatan." / ".$model->kurikulum ?></td></tr>
                <tr><th> Masuk Perkuliahan </th><td>  <?= $model->angkatan." / ".$model->kurikulum ?></td></tr>


                <tr><th>Tarif</th><td><?= $model->tarif->kode_tarif ?> </td></tr>
            </table>
        </div>

    </div>
    <div class="clearfix"></div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);"><span style="font-size:14px;font-weight:bold"> INVOICE </span></div>
        <div style="clear: both"></div>
    </div>

    <div class="clearfix"></div>
</div>


