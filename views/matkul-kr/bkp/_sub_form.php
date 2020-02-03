<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

$PR = \app\models\Program::find()->where(
        "jr_id=$Parent->jr_id and pr_kode not in(select pr_id from matkul_kr where parent=$Parent->id)"
);
$arr=[];
foreach ($PR->all() as $d){$arr[$d->pr_kode]="$d->pr_nama | $d->pr_en";}
?>
<div class="panel panel-info">
    <div class="panel panel-heading"><h4 class="panel-title">Form Sub Kurikulum</h4></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'pr_id'=>[
                    'label'=>'Konsentrasi',
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'options'=>[
                        'data' => $arr?$arr:[""],
                        'options' => [
                            'fullSpan'=>6,
                            'placeholder' => 'Konsentrasi ',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],
                ],
                'ket' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Keterangan']],
                [
                    'label'=>'',
                    'type'=>Form::INPUT_STATIC,
                    'staticValue'=> Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','style'=>'text-align:right']
                    )
                ],
            ]
        ]);
        ActiveForm::end(); ?>
    </div>
</div>
