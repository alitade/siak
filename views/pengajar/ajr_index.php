<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


use app\models\Funct;

$this->title = 'Daftar Pengajar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bobot-nilai-index">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>0], 'method' => 'get',]);
    echo Form::widget([
        'form' => $form,
        'model'=>$searchModel,
        'columns' =>1,
        'attributes' => [
            'tahun'=>[
                'label'=>'',
                'attributes'=>[
                    'kr_kode' =>[
                        'label'=>false,
                        'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
                        'options'=>[
                            'data' =>app\models\Funct::AKADEMIK(),
                            'options' => ['fullSpan'=>6,'placeholder' => 'Pilih Tahun Akademik'],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'label'=>'',
                        'type'=>Form::INPUT_RAW,
                        'value'=>
                            Html::submitButton(Yii::t('app', 'Cari Data Pengajar'),['class' =>'btn btn-primary','style'=>'text-align:right'])
                            ." ".(Funct::acc('/pengajar/create') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Data Pengajar', ['/pengajar/create'], ['class' => 'btn btn-success']) : "")
                    ],
                ],
            ],
        ]
    ]);
    ActiveForm::end();
    ?>
    <?php
    if(isset($_GET['BobotNilaiSearch']['kr_kode']) && !empty($_GET['BobotNilaiSearch']['kr_kode'])) {
		Pjax::begin();
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
			'pjax'=>true,
            'toolbar' => [
                ['content' =>
                    (Funct::acc('akademik/report-pengajar') ? Html::a('<i class="glyphicon glyphicon-download-alt"></i> Export', ['akademik/report-pengajar'], ['class' => 'btn btn-info']) : "")
                ],
                '{toggleData}',
            ],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'kr_kode',
                    'label' => 'Tahun Akademik',
                    'value' => function ($model) {
                        return @$model->kln->kr_kode;
                    },
                    'width' => '10%'
                ],
                [
                    'attribute' => 'jr_id',
                    'label' => 'Jurusan',
                    'value' => function ($model) {
                        return @Funct::JURUSAN()[$model->kln->jr_id];
                    },
                    'filterType' => GridView::FILTER_SELECT2,
					'filter'=>Funct::JURUSAN(1,($subAkses['jurusan']?['jr_id'=>$subAkses['jurusan']]:"")),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Jurusan'],


                ],
                [
                    'attribute' => 'pr_kode',
                    'label' => 'Program',
                    'value' => function ($model) {
                        return @Funct::PROGRAM()[$model->kln->pr_kode];
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => app\models\Funct::PROGRAM(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Program'],

                ],

                [
                    'attribute' => 'mtk_kode',
                    'value' => function ($model) {
                        return @Funct::MTK()[@$model->mtk_kode];
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => app\models\Funct::MTK(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '...'],
                ],


                [
                    'attribute' => 'ds_nidn',
                    'value' => function ($model) {
                        return $model->ds->ds_nm;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => app\models\Funct::DSN(1, 'ds_id'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '...'],
                ],
                [
                    #'attribute' => 'tot',
                    'header' => '&sum;Jadwal',
                    'format' => 'raw',
                    'value'=>function($model){
                        return count($model->jdw);
//                        return Html::a($model->tot,
//                        Yii::$app->urlManager->createUrl(['bisa/ajr-jdw',
//                        'id'=> $model->id,
//                        'edit'=>'t'])
//                        ,['title' => Yii::t('yii', 'Bobot'),]);
                    },
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {delete} {view}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if (!Funct::acc('/pengajar/update')) {return false;}
                            return Html::a('<span class="fa fa-pencil"></span>',
                                Yii::$app->urlManager->createUrl(['/pengajar/update', 'id' => $model->id, 'edit' => 't'])
                                , ['title' => Yii::t('yii', 'Edit'),]);
                        },
                        'view' => function ($url, $model) {
                            if (!Funct::acc('/pengajar/view')) {return false;}
                            return Html::a('<span class="fa fa-eye"></span>',
                                Yii::$app->urlManager->createUrl(
                                    ['/pengajar/view', 'id' => $model->id, 'view' => 't']),
                                ['title' => Yii::t('yii', 'Detail'),]
                            );
                        },
                        'delete' => function ($url, $model) {
                            if (!Funct::acc('/pengajar/delete')) {return false;}
                            return Html::a('<span class="fa fa-trash"></span>',
                                Yii::$app->urlManager->createUrl(
                                    ['/pengajar/delete', 'id' => $model->id, 'delete' => 't']), [
										'title' => Yii::t('yii', 'Hapus'),
										'data' => [
											'confirm' => "Hapus Data ini?",
											'method' => 'post',
										],
									]
                            );
                        },

                    ],
                ],
            ],
            'responsive' => true,
            'hover' => true,
            'condensed' => true,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<i class="fa fa-navicon"></i> Daftar Pengajar',
            ]
        ]);
		Pjax::end();
    }
    ?>

</div>
