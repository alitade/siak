<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\modules\keuangan\models\Tarif $model
 */

$this->title = 'Tambah Data Tarif';
$this->params['breadcrumbs'][] = ['label' => 'Tarif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="panel">
    <div class="panel-heading">
        <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
            <span style="font-size:14px;font-weight:bold"> Tambah Tarif Baru</span>
            <div class="pull-right"></div>
        </div>
        <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'">

        </span>
        <div style="clear: both"></div>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'formConfig'=>['labelSpan'=>1]
        ]);

        echo Form::widget([
            'model'=>$mTarifD,
            'form'=>$form,
            'columns'=>1,
            'attributes'=>[
                [
                    'columns'=>3,
                    'label'=>'Tarif',
                    'attributes'=>[
                        'dpp'=>[
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\yii\widgets\MaskedInput',
                            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Rp'],],],
                            'options'=>[
                                'clientOptions' => [
                                    'alias' =>'decimal',
                                    'groupSeparator'=>',',
                                    'autoGroup' =>true
                                ],
                            ],
                        ],
                        'urutan'=>[
                            'type'=>Form::INPUT_TEXT,
                            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Ke-'],],],
                        ],


                    ]

                ],
                'tipe'=>[
                    'label'=>'Jenis Pembayaran',
                    'type'=>Form::INPUT_RADIO_LIST,
                    'items'=>[0=>'Denda','Wajib']
                ],
                [
                    'type'=>Form::INPUT_RAW,
                    'value'=>Html::submitButton('<i></i> Simpan',['class'=>'btn btn-success pull-right'])
                ],

            ]
        ]);
        ActiveForm::end()
        ?>

    </div>

    <?php if(isset($_SESSION['tarif'])): ?>
        <div class="panel-body">
            <div class="col-sm-6">
                <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                    <span style="font-size:14px;font-weight:bold">Kriteria Tarif</span>
                    <div class="pull-right"></div>
                </div>
                <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'"></span>
                <div style="clear: both"></div>
                <p> </p>
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,]); ?>
                <?=
                Form::widget([
                    'model' => $mTarif,
                    'form'  => $form,
                    'columns'=>1,
                    'attributes'=>['id'=>['label'=>'Kode','type'=>Form::INPUT_TEXT,'options'=>['placeholder'=>'Kode tarif']]]

                ]);
                ?>

                <?= Form::widget([
                    'model' => $model,
                    'form' =>$form,
                    'columns' => 1,
                    'attributes' =>[
                        'jnsBayar'=>[
                            'label'=>'Pembayaran',
                            'type'=> Form::INPUT_RADIO_LIST,
                            'items'=>[0=>'Paket','Cicilan'],
                            'options'=>['inline' =>true,],
                        ],
                        'vendor'=>[
                            'label'=>'Vendor',
                            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                            'options'=>[
                                'data' => app\models\Functdb::vendor(),
                                'options' => ['fullSpan'=>6,'placeholder' => 'Konsultan',],
                                'pluginOptions' => ['allowClear' => true],
                            ],
                        ],
                        'fk'=>[
                            'label'=>'Fakultas',
                            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                            'options'=>[
                                'data' => app\models\Functdb::fakultas(),
                                'options' => ['fullSpan'=>6,'placeholder' => 'fakultas',],
                                'pluginOptions' => ['allowClear' => true],
                            ],
                        ],
                        'jr'=>[
                            'label'=>'Jurusan',
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
                            'options'=>[
                                'type'=>2,
                                'options' => ['fullSpan'=>6,'placeholder' => '... ',],
                                'select2Options'=>	['pluginOptions'=>['allowClear'=>true]],
                                'pluginOptions' => [
                                    'depends'		=>	['biaya-fk'],
                                    'url' 			=> 	Url::to(['/json/fkjr']),
                                    'loadingText' 	=> 	'Loading...',
                                    #'params'=>['input-type-1', 'input-type-2']
                                ],
                            ],
                        ],
                        'pr'=>[
                            'label'=>'Program',
                            'type'=> Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                            'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Program'],],],
                            'options'=>[
                                'data' => app\models\Functdb::program()  ,
                                'options' => ['fullSpan'=>6,'placeholder' => 'Program',],
                                'pluginOptions' => ['allowClear' => true],

                            ],
                        ],
                        [
                            'columns'=>2,
                            'attributes'=>[
                                'thn'=>[
                                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Angkatan'],],],
                                ],
                                'kurikulum'=>[
                                    'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                                    'fieldConfig'=>['addon'=>['prepend' =>['content'=>'Kurikulum'],],],
                                ],
                            ]

                        ],
                        'jns'=>[
                            'label'=>' ',
                            'type'=> Form::INPUT_RADIO_LIST,
                            'items'=>[0=>'Baru','Linier','Non Linier'],
                            'options'=>['inline'=>true],
                        ],
                        'ket'=>[
                            'type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>''],
                        ],
                        [
                            'type'=>Form::INPUT_RAW,
                            'value'=>Html::submitButton('<i></i> Simpan',['class'=>'btn btn-success pull-right'])
                        ],
                    ]
                ]);
                ?>
                <?php ActiveForm::end() ?>

            </div>


            <div class="col-sm-6">
                <div style="border-bottom:solid 1px rgba(0,0,0,0.3);">
                    <span style="font-size:14px;font-weight:bold">Daftar Tarif <?= Html::a('<i class="fa fa-trash"></i> Hapus Tarif',['add-biaya','d'=>1],['class'=>'pull-right']);?></span>
                    <div class="pull-right"></div>
                </div>
                <span style="font-size:12px;font-weight:normal;font-family:'Tahoma'"></span>
                <div style="clear: both"></div>
                <p> </p>



                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tarif</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $n=0;

                    foreach($_SESSION['tarif'] as $k=>$v): $n++; ?>
                        <tr>
                            <td><?= $n ?></td>
                            <td><?= number_format($v) ?></td>
                            <td><?= Html::a('<i class="fa fa-trash"></i>',['add-biaya','dt'=>$k]) ?></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>


</div>

<?php
$this->registerJs('
    $(document).ready(
        function(){
            $("#biaya-vendor").change(function(){$("#biaya-kdvendor").val($("#biaya-vendor").val());});                
        }
    );

');
