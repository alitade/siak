<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

use app\models\Funct;


/**
 * @var yii\web\View $this
 * @var app\models\Konsultan $model
 */

$this->title = "Program Kuliah ";
#$this->params['breadcrumbs'][] = ['label' => " Konsultan ", 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => " Konsultan ".$model->vendor, 'url' => ['#']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'options'=>[
        'tabindex' => false
    ],
    'header'=>'Tambah Data Program Perkuliahan Mahasiswa',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modals'
]);

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>1]]);
echo Html::hiddenInput('idk',$model->id,['id'=>'idk']);
echo Form::widget([
    'model' => $modKonPr,
    'form' => $form,
    'columns' =>1,
    'attributes' => [
        'program_id'=>[
            'label'=>false,
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' => \app\models\Functdb::program() ? \app\models\Functdb::program():[""],
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Program Perkuliahan',
                    'multiple'=>false,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ],
        ],
        'jurusan_id'=>[
            'label'=>false,
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\DepDrop',
            'options'=>[
                'type'=>2,
                'options' => [
                    'fullSpan'=>6,
                    'placeholder' => 'Jurusan','multiple'=>true,
                ],
                'select2Options'=>	[
                    'pluginOptions'=>['allowClear'=>true]
                ],
                'pluginOptions' => [
                    'depends'		=>	['konsultanprogram-program_id'],
                    'url' 			=> 	Url::to(['/json/un-konjrpr','t'=>'jr']),
                    'loadingText' 	=> 	'Loading...',
                    'params'=>['idk']
                ],
            ],
        ],
        [
            'type'=>Form::INPUT_RAW,
            'label'=>false,
            'labelSpan'=>0,
            'value'=>Html::submitButton('<i class="fa fa-save"></i> Tambah Program',['class'=>'btn btn-primary'])

        ]
    ]
]);
ActiveForm::end();
Modal::end();
?>
<?php Pjax::begin(); echo GridView::widget([
    'dataProvider' => $dataProvider,
    #'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'pr.pr_nama',
        [
            'label'=>'Jurusan',
            'value'=>function($model){return $model->jr->jr_jenjang.' '.$model->jr->jr_nama;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fa fa-trash"></i>',
                        Yii::$app->urlManager->createUrl(['konsultan/del-pr','jr' => $model->jurusan_id, 'k' => $model->konsultan_id, 'pr' => $model->program_id]), [
                        'title' => Yii::t('yii', 'Hapus'),'data-method'=>'post','data-confirm'=>'Hapus Data Ini?'
                    ]);}

            ],
        ],
    ],
    'responsive'=>true,
    'hover'=>true,
    'condensed'=>true,
    'floatHeader'=>true,
    'panel' => [
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).'Konsultan '.$model->vendor." ($model->kode)".' </h3>',
        'type'=>'primary',
        'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success','id'=>'popupModal']),
        'after'=>false,
        'footer'=>false,
        'showFooter'=>false,
    ],
]); Pjax::end();

?>
<?php
$this->registerJs("$(function() {
   $('#popupModal').click(function(e) {
     e.preventDefault();
     $('#modals').modal('show').find('.modal-content').html(data);
   });
});");
