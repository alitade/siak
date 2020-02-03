<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Funct;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\builder\Form;

use kartik\grid\GridView;
use yii\widgets\Pjax;

#echo $modUser->id;
#Funct::v(\app\models\Functdb::unAkses($modUser->id));

$linkImg=Url::to('@web/pt/no_foto.jpg');
if(file_exists("../web/pt/".$model->photo) && $model->photo){$linkImg=Url::to("@web/pt/".$model->photo);}

$ctgl=explode(" ",$model->ctgl);


?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
if($searchModel->jr_id){echo "<span class='badge bg-info'> $searchModel->jr_id </span> ";}
?>
    <div class="panel">
        <div class="panel-heading" >
            <span class="panel-title"> Akses Kontrol Pengguna</span>
            <div class="pull-right">
                <?= $sm ? Html::a('<i class="fa fa-refresh"></i> Reset Password',['biodata/reset-pass','id'=>$modUser->id],['class'=>'btn btn-primary btn-sm']):''
                ?>
            </div>
            <p></p>
            <div class="clearfix" style="border-bottom: solid 1px #000"> </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 col-md-2">
                    <?= Html::img($linkImg,['class'=>'img img-thumbnail']) ?>
                    <div class="clearfix"></div>
                    <div class="pull-right">
                        <div style="margin-top: 2px;">
                            <?= ($model->photo?Html::a('<i class="fa fa-trash"></i> Hapus',['/biodata/del-pt','id'=>$model->id_],['class'=>'btn btn-primary btn-sm']):"") ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-10">
                    <table class="table table-bordered">
                        <tr><th width="90"> Nama </th><td><?= $model->nama ?></td></tr>
                        <tr><th> Username </th><td>
                            <?php

                            if($modUser->isNewRecord) {
                                $form = ActiveForm::begin([
                                        'type' => ActiveForm::TYPE_INLINE,
                                        'formConfig'=>['showErrors'=>true]
                                    ]);
                                #/*
                                echo Form::widget([
                                    'model' => $modUser,
                                    'form' => $form,
                                    'columns' => 1,
                                    'attributes' => [
                                        'username' => ['label' => false, 'type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Username ...','size'=>50]],
                                        #'password' => ['label' => false, 'type' => Form::INPUT_PASSWORD, 'options' => ['placeholder' => 'Password ...']],
                                        [
                                            'type' => Form::INPUT_RAW,
                                            'value' => Html::submitButton('<i class="fa fa-save"></i> Simpan Data User', ['class' => 'btn btn-primary'])

                                        ]
                                    ]
                                ]);
                                ActiveForm::end();
                            }else{echo $modUser->username;}

                            ?>

                            </td></tr>
                        <tr><th>No.Tlp.</th><td><?= $model->tlp ?></td></tr>
                        <tr><th>Email</th><td><?= Yii::$app->formatter->asEmail($model->email)  ?></td></tr>
                    </table>
                    <?php
                    ?>

                </div>
            </div>

            <hr style="border: solid 1px #000;">
            <?php

            if($modUser->id_bio){
                Modal::begin([
                    'options'=>['tabindex' => false],
                    'header'=>'Tambah Akses Pengguna '.$modUser->username,
                    'headerOptions'=>['class'=>'bg-primary'],
                    'size'=>'modal-lg',
                    'id'=>'modals'
                ]);

                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2]]);
                echo Form::widget([
                    'form' => $form,
                    'formName'=>'kode',
                    'columns' => 2,
                    'attributes' => [
                        'kode'=>[
                            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                            'options'=>[
                                'data' => \app\models\Functdb::unAkses($modUser->id)?:[""],
                                'options' => ['fullSpan'=>6,'placeholder' => 'Daftar Akses','multiple'=>true],
                                'pluginOptions' => ['allowClear' => true],
                            ],
                        ],
                        [
                            'label'=>'',
                            'type'=>Form::INPUT_RAW,
                            'value'=> Html::submitButton(Yii::t('app', 'Tambah Akses'),['class' =>'btn btn-primary','style'=>'text-align:right','name'=>'add']
                            )
                        ],
                    ]
                ]);
                ActiveForm::end();
                Modal::end();


                $form = ActiveForm::begin([
                    'type'=>ActiveForm::TYPE_VERTICAL,
                    'id'=>'create-krs',
                    #'action' => Url::to(['krs/ds-app']),
                    'method'=>'post'
                ]);

                $btn=Html::a('<i class="fa fa-plus"></i> Tambah Akses',['#'],['class'=>'btn btn-primary btn-sm', 'id'=>'popupModal'])." ".Html::submitButton('<i class="fa fa-trash"></i> Hapus Data Terpilih',['class'=>'btn btn-primary btn-sm','name'=>'del']);

                echo GridView::widget([
                    'dataProvider' => $dataAkses,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn','width'=>'1%',],
                        [
                            'class'=>'kartik\grid\CheckboxColumn',
                        ],
                        [
                            'attribute'=>'description',
                            'header'=>'Akses: Keterangan',
                            'value'=>function($model){
                                return "<b>$model->name : </b><i>".($model->description?:"-----")."</i>";
                            },
                            'format'=>'raw',
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'condensed'=>true,
                    'floatHeader'=>true,
                    'toolbar'=>false,
                    'panel' => [
                        'heading'=>false,
                        'type'=>'info',
                        'before'=>$btn,
                        'after'=>$btn,
                        'showFooter'=>false,
                        'footer'=>false,
                    ],
                ]);
                ActiveForm::end();


            }

            ?>

        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-10 col-md-10"><?= $LOG ?></div>
                <div class="col-sm-2 col-md-2 pull-right"><?= Html::a('<i class="fa fa-th-list"></i> Daftar Riwayat Akses',['/biodata/log-list','id'=>$model->id_],['target'=>'_blank'])?></div>
            </div>
        </div>
    </div>
<?php

