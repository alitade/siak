<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <p class="panel-title">
            Pengaturan Bobot Nilai Tahun Akademik <?= $model->kln->kr->kr_nama?>
        </p></div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th width="50%"><?= $model->ds->ds_nm ?></th>
                <th><?= $model->kln->jr->jr_jenjang." ".$model->kln->jr->jr_nama ?></th>
            </tr>
            <tr>
                <th><?= $model->mtk->mtk_kode.":".$model->mtk->mtk_nama ?></th>
                <th><?= $model->kln->pr->pr_nama ?></th>
            </tr>

        </table>
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>3]]);?>
        <div class="col-sm-6">
            <h4>Persentase Nilai </h4>
            <?php
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'nb_tgs1'=>['label'=>'% Tugas 1','type'=> Form::INPUT_TEXT,],
                    'nb_uts'=>['label'=>'% UTS','type'=> Form::INPUT_TEXT,],
                    'nb_tgs2'=>['label'=>'% Tugas 2','type'=> Form::INPUT_TEXT,],
                    'nb_uas'=>['label'=>'% UAS','type'=> Form::INPUT_TEXT,],
                    'nb_quis'=>['label'=>'% Quiz','type'=> Form::INPUT_TEXT,],
                    'nb_tambahan'=>['label'=>'% Kehadiran','type'=> Form::INPUT_TEXT,],
                ]
            ]);
            ?>
            <div style="font-size:12px;font-weight:bold"><i>*) Total nilai keseluruhan inputan harus berjumlah 100</i></div>
        </div>
        <div class="col-sm-6">
            <h4>Grade Nilai </h4>
            <?php
            echo Form::widget([

                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'B'=>['type'=> Form::INPUT_TEXT,],
                    'C'=>['type'=> Form::INPUT_TEXT,],
                    'D'=>['type'=> Form::INPUT_TEXT,],
                    'E'=>['type'=> Form::INPUT_TEXT,],
                ]
            ]);
            ?>
            <div style="font-size:12px;font-weight:bold">
                <i> *) Nilai yang diisi merupakan range tertinggi dari grade<br>
                    *) Nilai diisi dengan bilangan desimal 2 angka di belakang koma (50.00)<br>
                    *) Gunakan tanda titik(.) sebagai pemisah desimal
                </i></div>
        </div>
        <div class="col-sm-12">
            <?php
            echo Html::submitButton('Simpan',['class' =>'btn btn-primary'])." ";
            echo Html::submitButton('Nilai Default',['class' =>'btn btn-primary','name'=>'dev'])." ";
            ?>
        </div>

        <?php

        ActiveForm::end(); ?>


    </div>
</div>





