<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

$KR = \app\models\MatkulKr::findAll();
$arr=[];
foreach ($KR as $d){$arr[]=$d->kode;}
?>
<p></p>
<div class="panel panel-info">
    <div class="panel panel-heading"><h4 class="panel-title">Form Kurikulum Matakuliah</h4></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>1]]);
        if($EDT){
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    [
                        'label'=>'Kode / Jurusan',
                        'type'=>Form::INPUT_STATIC,
                        'staticValue'=>$model->kode." / ".app\models\Funct::JURUSAN()[$model->jr_id],
                    ],
                    'totSks'=>['label'=>'Total SKS','type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Total Sks','readonly'=>($q['t']>0?true:false),]],
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
        }else{
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'jr_id'=>[
                        'label'=>'',
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' => app\models\Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                            'options' => [
                                'fullSpan'=>6,
                                'placeholder' => 'Pilih Jurusan',
                            ],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'label'=>false,
                        'columns'=>3,
                        'labeSpan'=>1,
                        'attributes'=>[
                            'kode'=>[
                                'type'=>Form::INPUT_WIDGET,
                                'widgetClass'=>'\kartik\widgets\Typeahead',
                                'options'=>[
                                    'dataset' =>[[
                                        'local'=>$arr?$arr:[""],
                                        'limit'=>10,
                                    ]],
                                    'options' => [
                                        'fullSpan'=>6,
                                        'placeholder' => 'Kode Paket',
                                    ],
                                ],

                            ],
                            'totSks'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Total Sks','readonly'=>($q['t']>0?true:false),]],
                            'aktif'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\SwitchInput',],
                        ],
                    ],
                    'ket' => ['label'=>'','type' => Form::INPUT_TEXTAREA, 'options' => ['placeholder' => 'Keterangan']],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_RAW,
                        'value'=>'<div class="pull-right"> '.
                            Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','style'=>'text-align:right']
                        ).'</div>'
                    ],
                ]
            ]);
        }

        ActiveForm::end(); ?>
    </div>
</div>
