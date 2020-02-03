<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\builder\Form;


use app\models\Funct;

$this->title = 'Daftar Pengajar vakasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bobot-nilai-index">
    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig'=>['labelSpan'=>2], 'method' => 'get',]);
    echo Form::widget([
        'form' => $form,
        'model'=>$searchModel,
        'columns' =>2,
        'attributes' => [
            'kr_kode'=>[
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
                'value'=> Html::submitButton(Yii::t('app', 'Cari'),['class' =>'btn btn-primary','style'=>'text-align:right']
                )
            ],
        ]
    ]);
    ActiveForm::end();
    ?>
    <?php
    if(isset($_GET['BobotNilaiSearch']['kr_kode']) && !empty($_GET['BobotNilaiSearch']['kr_kode'])) {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'toolbar' => [
                ['content' =>'',
                ],
                '{toggleData}',
            ],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'ds_nidn',
					'format'=>'raw',
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
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (!Funct::acc('/pengajar/vakasi-detail')) {return false;}
                            return Html::a('<span class="fa fa-eye"></span>',
                                Yii::$app->urlManager->createUrl(
                                    ['/pengajar/vakasi-detail', 'id' => $model->id, 'view' => 't']),
                                ['title' => Yii::t('yii', 'Detail'),]
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
    }
    ?>

</div>
