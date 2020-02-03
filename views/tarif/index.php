<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\keuangan\models\TarifSearch $searchModel
 */

$this->title = 'Daftar Kode Tarif';
$this->params['breadcrumbs'][] = $this->title;
Modal::begin([
    'options'=>['tabindex' => false],
    'header'=>'<i class="fa fa-plus"></i> Tambah Kode Tarif',
    'size'=>'modal-lg',
    'headerOptions'=>['class'=>'bg-primary'],
    'id'=>'modalsAdd',
    'clientOptions'=>['show'=>($model->getErrors()?true:false),],

]);
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
echo $this->render('_form', ['model' => $model,'form'=>$form]);
echo $this->render('_info', ['model' => $mBiaya,'form'=>$form]);
echo '<div class="pull-right">'.Html::submitButton('<i></i> Simpan',['class'=>'btn btn-success pull-right']).' </div>';
echo'<div class="clearfix"></div>';
ActiveForm::end();
Modal::end();

?>

<?= Html::a('<i class="fa fa-plus"></i> Tambah Kode Tarif',['#'],['class'=>'btn btn-success','id'=>'_modalsAdd']).' ' ?>
<?= Html::a('<i class="fa fa-search"></i> Filter Data',['#'],['class'=>'btn btn-success','id'=>'_modalsSch']).' ' ?>
<p></p>
<div class="tarif-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'width'=>'1%',
            ],
            [
                'header'=>'Kode',
                'format'=>'raw',
                'value'=>function($model){
                    return '<span class="text-nowrap" style="font-weight: bold">'.$model->kode_tarif.'</span>';
                }
            ],

            [
                'header'=>'Keterangan',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->ket.'<br><i style="font-size: 12px;font-weight: bold;">'.$model->info.'</i>';
                }
            ],
            [
                'header'=>'<i class="fa fa-list"></i>',
                'format'=>'raw',
                'width'=>'1%',
                'value'=>function($model){
                    return '<i class="fa fa-remove" style="color:red;"></i>';
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'<span class="text-nowrap">{view} {update}</span>',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-eye"></i>', Yii::$app->urlManager->createUrl(['tarif/view','id' => $model->id,'edit'=>'t']),
                            [ 'title' => Yii::t('yii', 'Edit'),'class'=>'btn btn-success btn-xs']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fa fa-pencil"></i>', Yii::$app->urlManager->createUrl(['keuangan/tarif/view','id' => $model->id,'edit'=>'t']),
                            [ 'title' => Yii::t('yii', 'Edit'),'class'=>'btn btn-success btn-xs']
                        );
                    },

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'<span style="font-weight:bold;"><i class="fa fa-list"></i> : Status Daftar Tarif </span>',
            'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<?php
$this->registerJs("$(function() {
   $('#_modalsAdd').click(function(e) {
     e.preventDefault();
     $('#modalsAdd').modal('show').find('.modal-content').html(data);
   });
});");