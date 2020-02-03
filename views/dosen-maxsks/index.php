<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

$this->title = 'Bebasn SKS Dosen';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'method'=>'get','action'=>['index']]);
echo Form::widget([
    'model' => $searchModel,
    'form' => $form,
    'columns' =>2,
    'attributes' => [
        'tahun'=>[
            'label'=>'Kurikulum',
            'type'=>Form::INPUT_WIDGET,'widgetClass'=>'\kartik\widgets\Select2',
            'options'=>[
                'data' =>app\models\Funct::AKADEMIK(),
                'options' => ['fullSpan'=>6,'placeholder' => 'Pilih Kurikulum'],
                'pluginOptions' => ['allowClear' => true],
            ],
        ],
        ['type'=> Form::INPUT_RAW,'value'=>Html::submitButton('<i class="fa fa-search"></i> Cari Data', ['class'=>'btn btn-primary']),],
    ]

]);
ActiveForm::end(); ?>

        <div class="dosen-maxsks-index">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                #'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['header'=>'Kurikulum','value'=>function($model){return $model->tahun;}],
                    ['header'=>'Tipe','value'=>function($model){return $model->tipe->tipe;}],
                    ['header'=>'Max. SKS','value'=>function($model){return $model->maxsks;}],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-pencil"></span>', Yii::$app->urlManager->createUrl(['dosen-maxsks/update','id' => $model->id,]), [
                                    'title' => Yii::t('yii', 'Edit'),'class'=>'btn btn-primary btn-xs'
                                ]);}
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
                    #'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
                    #'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
                    'showFooter'=>false,
                    #'footer'=>false,
                ],
            ]);
            ?>

        </div>
    <?
    }else{
        echo "<div class='alert alert-info text-center' style='font-weight: bold'>
        <span style='color: #000;'>Data Beban SKS Kurikulum ".$searchModel->tahun." Tidak Ada.</span><br>".
            Html::a('Klik Tombol Ini Untuk Generate Beban SKS',['index','DosenMaxsksSearch[tahun]'=>$searchModel->tahun,'g'=>1],['class'=>'btn btn-primary'])
            ."
        </div>" ;
    }
}else{
    echo "<div></div>";
}
?>
