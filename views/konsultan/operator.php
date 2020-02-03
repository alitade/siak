<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\Funct;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

use yii\web\JsExpression;


$this->title = 'Operator Konsultan '.$model->vendor;
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'Tambah Operator Konsultan '.$model->vendor,
    #'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);
Modal::end();

$idKons=$model->kode;
?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title"> Tambah data Operator <?= $model->vendor ?> </span> </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'formConfig'=>['labelSpan'=>1]
        ]);
        echo Form::widget([
            'model' =>$modOp,
            'form' => $form,
            'columns' =>4,
            'attributes' => [
                'id_bio'=>[
                    'label'=>false,#'[No. Ktp] Nama',
                    'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                    'columnOptions'=>['colspan'=>3],
                    'options'=>[
                        'options' => [
                            'placeholder' => '[No. Ktp] Nama',
                        ],
                        'pluginOptions' => [
                            'width' => '100%',
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => ['errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['json/bio']),
                                'dataType' =>'json',
                                'data' => new JsExpression('function(params){return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function(markup){ return markup; }'),
                            'templateResult' => new JsExpression('function(bio){ return bio.text; }'),
                            'templateSelection' => new JsExpression('function(bio){ return bio.text; }'),

                        ],

                    ],
                ],
                [
                    'type'=>Form::INPUT_RAW,
                    'value'=>Html::submitButton('<i class="fa fa-save"></i> Simpan Data', ['class' => 'btn btn-primary'])
                ],
            ]

        ]);
        ActiveForm::end();

        ?>
    </div>
</div>

    <div class="biodata-index">
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            #'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label'=>'No. KTP',
                    'value'=>function($model){return $model->bio->no_ktp;},
                ],
                [
                    'label'=>'Nama',
                    'value'=>function($model){return $model->bio->nama;},
                ],
                [
                    'header'=>'<i class="fa fa-sign-in"></i>',
                    'format'=>'raw',
                    'width'=>'1%',
                    'value'=>function($model){
                        if($model->sign==1){
                            return '<i class="fa fa-check" style="color:green"></i>';
                        }
                        return '<i class="fa fa-remove" style="color:red"></i>';

                    },


                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template'=>'{access} {delete}',
                    'buttons' => [
                        'access' => function ($url, $model){
                            return Html::a('<span class="fa fa-gears"></span>',
                                Yii::$app->urlManager->createUrl(['biodata/akses','id'=> $model->id_bio]),
                                ['target'=>'_blank','title'=>'Akses Kontrol']);},

                        'delete' => function ($url, $model){
                            return Html::a('<span class="fa fa-trash"></span>',
                                Yii::$app->urlManager->createUrl(['konsultan/delete-op','id'=> $model->id_konsultan,'op'=>$model->id_bio]),
                                ['data-confirm'=>'Hapus Data Ini?','data-method'=>'post']);},

                    ],
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'floatHeader'=>true,
            'toolbar'=>false,
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                'type'=>'info',
                'before'=>'[<i class="fa fa-sign-in"></i> : Status Akses Sistem ]',
                'after'=>'',
                'showFooter'=>false,
                'footer'=>false,
            ],
        ]);
        ?>
    </div>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
